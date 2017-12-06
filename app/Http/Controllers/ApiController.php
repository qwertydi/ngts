<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Auth;

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

    public function stream(Request $request) {
        if(Request::hasFile('file')){
            $file = Request::file('file');
            $filename = $file->getClientOriginalName();
            var_dump($filename);
            $path = public_path().'/uploads/';
            return $file->move($path, $filename);
        }
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
        }elseif($type == "create todo"){
            $validator = Validator::make($request->all(),[
                'todo' => 'required',
                'description' => 'required',
                'category' => 'required'
            ]);
            if($validator->fails()){
                $error = true;
                $errors = $validator->errors();
            }
        }elseif($type == "update todo"){
            $validator = Validator::make($request->all(),[
                'todo' => 'filled',
                'description' => 'filled',
                'category' => 'filled'
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
                'active' => 'required'
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
     * Add a device
     */
    public function deviceAdd(Request $request)
    {
        $error = $this->validations($request,"create device");
        if ($error['error']) {
            return $this->prepareResult(false, [], $error['errors'],"Error in creating todo");
        } else {
            $device = DB::table('devices')->insert(['owner_id' => $request->user()->id, 'name' => $request->name, 'ip_address' => $request->ip_address, 'active' => $request->active]);

            return $this->prepareResult(true, $device, $error['errors'],"Device created");
        }
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
}