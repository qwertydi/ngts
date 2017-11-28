<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Profile;
use App\Models\Theme;
use App\Models\User;
use App\Models\Device;
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
        $devices = DB::table('devices')->where('owner_id',$user->id)->get();

        $data = [
            'devices' => $devices,
        ];

        return view('devices.show')->with($data);
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
