<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Profile;
use App\Models\Theme;
use App\Models\User;
use App\Models\Device;
use App\Models\Motion;
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



use Illuminate\Support\Facades\Mail;
use App\Mail\EmailController;

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
        // get alarm from database
        $alarm = [
            "alarms" => [
                "id" => "1"
            ],
        ];
        return view('devices.alarms')->with($alarm);
    }

    public function addAlarm(){
        // todo add
    }

    public function editAlarm(){
        // todo edit
    }

    public function deleteAlarm($id){
        // todo delete
    }

    public function storeAlarm(){
        // todo store
    }

    public function sendMail(){
        Mail::to(Auth::user())->send(new EmailController(Auth::user()));

        return redirect()->back();
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
        $stream = "http://" . $ip  . "/stream";
        $capture = $ip  . "/capture";

        $data = [
            'stream' => $stream,
            'capture' => $capture,
            'id' => $id,
        ];
        

        return view('devices.surveillance')->with($data);
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
                'ip_address'                 => 'required|max:255',
                'mac_address'               => 'required|max:255',
                'active'               => 'required|max:2',
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

        $device = new Device;
        $device->timestamps = false;
        $device->name = $request->input('name');
        $device->owner_id = $user->id;
        $device->ip_address = $request->input('ip_address');
        $device->mac_address = $request->input('mac_address');
        $device->active = $request->input('active');
        $device->save();

        var_dump($device->active);

        return redirect('devices')->with('success', trans('devices.createSuccess'));
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
