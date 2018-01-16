<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Profile;
use App\Models\Theme;
use App\Models\User;
use App\Models\Device;
use App\Models\Motion;
use App\Models\Alarms;
use App\Models\Picture;
use App\Notifications\SendGoodbyeEmail;
use App\Traits\CaptureIpTrait;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Image;
use jeremykenedy\Uuid\Uuid;
use Validator;
use View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailController;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class DevicesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param string $username
     *
     * @return Response
     */
    public function devicesByUser()
    {
        $user = Auth::user();
        $devices = Device::where('owner_id',$user->id)->get();
        $data = [
            'devices' => $devices,
        ];

        return view('devices.show')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param string $device
     *
     * @return Response
     */
    public function deleteDevice($id)
    {
        $device = Device::findOrFail($id)->get();

        if ($device) {
            $device = Device::destroy($id);
            $data = 'removed succesfully';
        } else {
            // warning
            return redirect('/devices')->with('error', trans('devices.errorDeviceDeleted'));
        }

        return redirect('/devices')->with('success', trans('devices.deviceDeleted'));        
    }

    public function alarms(){
        $user = Auth::user();
        $devices = Device::where('owner_id','=',$user->id)->get();
        $alarms = [];
        foreach ($devices as $d) {
            $alarms = Alarms::where('device_id','=',$d->id)->get();
        }
        //var_dump($alarms);
        $data = [
            "alarms" => $alarms,
        ];
        return view('alarms.show')->with($data);
    }

    public function addAlarms(){
        // Getting devices
        $user = Auth::user();
        $devices = Device::where('owner_id','=',$user->id)->get();
        $data = [
            'devices' => $devices,
        ];
        // view drawing
        return view('alarms.add')->with($data);
    }

    public function editAlarm($id){
        $user = Auth::user();
        $devices = Device::where('owner_id','=',$user->id)->get();
        $alarm = Alarms::findOrFail($id);

        $data = [
            'alarm'        => $alarm,
            'devices' => $devices,
        ];

        return view('alarms.edit')->with($data);
    }

    public function updateUserAccount(Request $request, $id) {
        // alarms.edit
    }

    public function deleteAlarm($id){
        $alarm = Alarms::findOrFail($id)->get();

        if ($alarm) {
            $alarm = Alarms::destroy($id);
            $data = 'removed succesfully';
        } else {
            // warning
            return redirect('/alarms')->with('error', trans('alarms.errorDeviceDeleted'));
        }

        return redirect('/alarms')->with('success', trans('alarms.deviceDeleted'));     
    }

    public function alarmStore(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(),
            [
                'device_id'               => 'required',
                'type'               => 'required',
                'start_hour'  => 'date_format:H:i|nullable',
                'end_hour'  => 'date_format:H:i|nullable',
            ],
            [
                'device_id.required'  => trans('alarms.device_id'),
                'type.required'=> trans('alarms.active'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $alarm = new Alarms;
        $alarm->timestamps = false;
        $alarm->device_id = $request->input('device_id');
        $alarm->type = $request->input('type');
        switch ($request->input('type')) {
            case 0:
            if ($request->input('start_hour') == null || $request->input('end_hour') == null) {
                return back()->withErrors(trans('alarms.error_hour'));
            }
            $start = Carbon::parse($request->input('start_hour'));
            $end = Carbon::parse($request->input('end_hour'));
            $alarm->start_hour = $start;
            $alarm->end_hour = $end;
            break;
            case 1:
            $alarm->start_hour = Carbon::parse('00:00');
            $alarm->end_hour = Carbon::parse('00:00');
            break;
            case 2:
            $alarm->start_hour = Carbon::parse('00:00');
            $alarm->end_hour = Carbon::parse('00:00');
            break;
            default:
            break;
            // alarm disabled idk
        }
        $alarm->save();


        return redirect('alarms')->with('success', trans('alarms.createSuccess'));
    }
    
     /**
     * Display the specified resource.
     *
     * @param string $surveillance
     *
     * @return Response
     */
    public function surveillance($id){
        $user = Auth::user();
        $device = Device::findOrFail($id)->get();

        if(Device::findOrFail($id)->owner_id != $user->id){
            return response()->view('errors.403');
        }

        $device = Device::where('id',$id)->get();

        $ip = $device[0]->ip_address;
        $type = $device[0]->type;
        if ($device[0]->type == 2) {
            $stream = "http://" . $ip  . "/stream";
            $capture = "http://" . $ip  . "/capture";
        } else {
            $stream = "http://" . $ip  . ":5000/stream";
            $capture = "http://" . $ip  . ":5000/capture";
        }
        

        $data = [
            'stream' => $stream,
            'capture' => $capture,
            'type' => $type,
            'id' => $id,
        ];
        

        return view('devices.surveillance')->with($data);
    }

    public function pictureButton($id){
        $user = Auth::user();
        $device = Device::findOrFail($id)->get();
        //dd($id);
        if(Device::findOrFail($id)->owner_id != $user->id){
            return response()->view('errors.403');
        }

        $device = Device::where('id',$id)->get();

        $ip = $device[0]->ip_address;
        $type = $device[0]->type;
        $stream = "http://" . $ip  . ":5000/stream";
        $capture = "http://" . $ip  . ":5000/capture";
        
        $client = new Client();  
        $msg;
        $result = $client->get($capture);
        
        if ( $result->getStatusCode() == 200) {
            $msg = 'success';
            $msg2 = trans('motion.pictureSuccess');           
         } else {
            $msg ='error';
            $msg2 = trans('motion.pictureError');
        }

        $data = [
            'stream' => $stream,
            'capture' => $capture,
            'type' => $type,
            'id' => $id,
            $msg => $msg2,
        ];

        return redirect('devices/'.$id.'/surveillance')->with($data);
    }

    public function pictureButton2($id){
        $user = Auth::user();
        $device = Device::findOrFail($id)->get();
        
        if(Device::findOrFail($id)->owner_id != $user->id){
            return response()->view('errors.403');
        }

        $device = Device::where('id',$id)->get();
    
        $client = new Client();  
        $ip = $device[0]->ip_address;
        $capture = "http://" . $ip  . ":5000/capture";
        $result = $client->get($capture);
        
        if ( $result->getStatusCode() == 200) {
            $msg = 'success';
            $msg2 = trans('motion.pictureSuccess');           
         } else {
            $msg ='error';
            $msg2 = trans('motion.pictureError');
        }

        $data = [
            'user' => $user,
            //'picture' => $picture,
            'id' => $id,
            $msg => $msg2,
        ];

        return redirect('devices/'.$id.'/pictures')->with($data);
    }


    public function showPictures($id){
        $user = Auth::user();
        if(Device::findOrFail($id)->get()){
            if(Device::findOrFail($id)->owner_id != $user->id){
            return response()->view('errors.403');
            }
            $picture = Picture::where('device_id',$id)->get();
            $data = [
                'user' => $user,
                'picture' => $picture,
                'id' => $id,
            ];
        }

        return view('devices.picture')->with($data);
    }
    

    /**
     * Display the specified resource.
     *
     * @param string $username
     *
     * @return Response
     */
    public function deviceHistory($id)
    {
        $user = Auth::user();
        if(Device::findOrFail($id)->get()){
            if(Device::findOrFail($id)->owner_id != $user->id){
            return response()->view('errors.403');
            }
            $motion = Motion::where('device_id',$id)->get();
            $data = [
                'user' => $user,
                'motion' => $motion,
                'id' => $id,
            ];
        }

        return view('devices.device')->with($data);
    }

    public function motionPictureHistory($id,$p_id)
    {
        $picture = Picture::findOrFail($id)->get();
        if ($picture) {
            $picture = Picture::destroy($id);
            //dd($picture);
            $data = 'removed succesfully';
        } else {
            // warning
            return redirect('/devices/'. $p_id.'/pictures')->with('error', trans('motion.errorMotionDeleted'));
        }

        return redirect('/devices/'.$p_id.'/pictures')->with('success', trans('motion.motionDeleted')); 
    }

    public function addDevice()
    {
        return view('devices.add');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(),
            [
                'name'                  => 'required|max:255|',
                'ip_address'                 => 'required|ip|max:255',
                'mac_address'               => 'required|max:255',
                'active'               => 'required|max:1',
                'type'               => 'required|max:1',
            ],
            [
                'name'         => trans('devices.name'),
                'name.required'       => trans('devices.name'),
                'ip_address.required'  => trans('devices.ip_address'),
                'mac_address.required'      => trans('devices.macAddress'),
                'active.required'=> trans('devices.active'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$this->IsValid($request->input('mac_address'))) {
            return  back()->withErrors("MAC Address not valid");
        }

        $device = new Device;
        $device->timestamps = false;
        $device->name = $request->input('name');
        $device->owner_id = $user->id;
        $device->ip_address = $request->input('ip_address');
        $device->mac_address = $request->input('mac_address');
        $device->active = $request->input('active');
        $device->type = $request->input('type');
        $device->save();

        return redirect('devices')->with('success', trans('devices.createSuccess'));
    }

    public static function IsValid($mac)
    {
        return (preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $mac) == 1);
    }


    /**
     * Display the specified resource.
     *
     * @param string $username
     *
     * @return Response
     */
    public function motionDeleteHistory($id, $m_id)
    {
        // TODO
        // get device 
        // delete device
        // redirect 
        $motion = Motion::findOrFail($m_id)->get();

        if ($motion) {
            $motion = Motion::destroy($m_id);
            $data = 'removed succesfully';
        } else {
            // warning
            return redirect('/devices/'.$id.'')->with('error', trans('motion.errorMotionDeleted'));
        }

        return redirect('/devices/'.$id.'')->with('success', trans('motion.motionDeleted'));     
    }
    
    /**
     * Fetch user
     * (You can extract this to repository method).
     *
     * @param $username
     *
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return User::with('profile')->wherename($username)->firstOrFail();
    }
}
