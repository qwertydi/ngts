<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Device;
use App\Models\Motion;
use App\Models\Alarms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailController;
use Auth;
use File;
use Image;

class ApiController extends Controller
{
    public $successStatus = 200;
    
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * Add a device
     */
    public function addDeviceFromPost(Request $request)
    {
        $error = $this->validations($request,"create device");
        if ($error['error']) {
            return $this->prepareResult(false, [], $error['errors'],"Error in adding device");
        } else {
            $device = Device::where('mac_address', '=', $request->mac_address)->get();
            $add = true;
            foreach($device as $d){
                if($d->mac_address == $request->mac_address){
                    $add = false;
                    return response()->json(['error'=>'Mac address already exist for this user!'], 401);
                }else{
                    $add = true;
                }
            }
            if($add){
                $add = new Device();
                $add->owner_id = $request->user()->id;
                $add->timestamps = false;
                $add->name =  $request->name;
                $add->ip_address = $request->ip_address;
                $add->mac_address = $request->mac_address;
                $add->active = $request->active;
                $add->save();
            }
            
            return $this->prepareResult(true, $device, $error['errors'],"Device created");
        }
    }

    /**
     * Add a device
     */
    public function deleteDeviceFromMAC(Request $request)
    {
        $error = $this->validations($request,"delete device");
        if ($error['error']) {
            return $this->prepareResult(false, [], $error['errors'],"Error in deleting device");
        } else {
            $exists = DB::table('devices')->where('mac_address', $request->mac_address)->select();
            // TODO
            var_dump($exist);
	        /*if($exists) {
                $device = DB::table('devices')->where('mac_address', $request->mac_address)->delete();
                return response()->json(['error'=>'Device deleted']);
            } else {
                return response()->json(['error'=>'Device doenst\'t exists']);
            }*/
        }
    }

    /**
     * Motion updater
     */
    public function addMotionFromPost(Request $request)
    {
        $error = $this->validations($request,"add motion");
        if ($error['error']) {
            return $this->prepareResult(false, [], $error['errors'],"Error on adding motion");
        } else {
            // check if parent exist!
            if(Device::findOrFail($request->device_id)){
                $add = new Motion();
                $add->device_id = $request->device_id;
                $add->timestamps = false;
                $add->date =  date('Y-m-d H:i:s');
                $add->stream = $request->stream;
                $add->picture = $request->picture;
                $add->save();
            } else {
                return response()->json(['error'=>'Device ID doesn\'t exist!'], 401);
            }
               
        }

        $this->emailNotificator($add);
        return $this->prepareResult(true, $add, $error['errors'],"Motion added");
    }
    
    /**
     * Email Notificator
     */
    public function emailNotificator($motion){
        $device = Device::where('id','=',$motion->device_id)->get();
        $user = User::where('id','=',$device[0]->owner_id)->get();
        $email = $user[0]->email;
        $motion = Motion::where('id','=',$motion->id)->get();

        $alarms = Alarms::where('device_id','=',$motion[0]->device_id)->get();
        if (count($alarms) > 0) {
            foreach ($alarms as $a) {
                if ($a->type == 0) {
                    //Se dentro do intervalo
                } else if ($a->type == 1) {
                    $this->sendMail($email);
                } else if ($a->type == 2) {
                    // nao faz nada
                } 
            }
        }
    }

    /**
     * Stream updater
     */
    public function stream(Request $request) {
        // TODO
    }
    

    /**
     * Get a validator for an incoming Todo request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $type
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validations($request,$type){
        $errors = [];
        $error = false;
        if($type == "login"){
            $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255',
            'password' => 'required',
            ]);
            if($validator->fails()){
                $error = true;
                $errors = $validator->errors();
            }
        }elseif($type == "delete device"){
            $validator = Validator::make($request->all(),[
                'mac_address' => 'required'
            ]);
            if($validator->fails()){
                $error = true;
                $errors = $validator->errors();
            }
        }elseif($type == "add motion"){
            $validator = Validator::make($request->all(),[
                'device_id' => 'filled',
                'date' => 'filled',
                'hour' => 'filled',
                'stream' => 'filled',
                'picture' => 'filled'
            ]);
            if($validator->fails()){
                $error = true;
                $errors = $validator->errors();
            }
        }elseif($type == "create device"){
            $validator = Validator::make($request->all(),[
                //'owner_id' => 'required',
                'name' => 'required',
                'ip_address' => 'required',
                'active' => 'required',
                'mac_address' => 'required'
            ]);
            if($validator->fails()){
                $error = true;
                $errors = $validator->errors();
            }
        }

        return ["error" => $error,"errors"=>$errors];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function prepareResult($status, $data, $errors,$msg)
    {
        return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];
    }

    /**
     * Display a listing of devices.
     */
    public function devicesUser(Request $request)
    {
        $devices = DB::table('devices')->where('owner_id',$request->user()->id)->get();

        $data = [
            'devices' => $devices,
        ];

        return ['status' => $devices];
    }
    
    /**
     * Current user information
     */
    public function user(Request $request)
    {
        return $request->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Todo $todo)
    {
        if($todo->user_id == $request->user()->id){
            return $this->prepareResult(true, $todo, [],"All results fetched");
        }else{
            return $this->prepareResult(false, [], "unauthorized","You are not authenticated to view this todo");
        }
    }

    /**
     * Metodo antigo
     */
    public function accessToken(Request $request)
    {
        $validate = $this->validations($request,"login");
        if($validate["error"]){
            return $this->prepareResult(false, [], $validate['errors'],"Error while validating user"); 
        }
        $user = User::where("email",$request->email)->first();
        if($user){
            if (Hash::check($request->password,$user->password)) {
                return $this->prepareResult(true, ["accessToken" => $user->createToken('Todo App')->accessToken], [],"User Verified");
            }else{
                return $this->prepareResult(false, [], ["password" => "Wrong passowrd"],"Password not matched");  
            }
        }else{
            return $this->prepareResult(false, [], ["email" => "Unable to find user"],"User not found");
        }
        
    }

    public function sendMail($email){
        Mail::to($email)->send(new EmailController());

        return redirect()->back();
    }
}