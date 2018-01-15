<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use ActivationTrait;
    use CaptchaTrait;
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/activate';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => 'logout',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        /*$data['captcha'] = $this->captchaCheck();
        $data['captcha'] = true;
        if (!config('settings.reCaptchStatus')) {
            $data['captcha'] = true;
        }*/

        return Validator::make($data,
            [
                'name'                  => 'required|max:255|unique:users',
                'first_name'            => '',
                'last_name'             => '',
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                //'g-recaptcha-response'  => '',
                //'captcha'               => 'required|min:1',
            ],
            [
                'name.unique'                   => trans('auth.userNameTaken'),
                'name.required'                 => trans('auth.userNameRequired'),
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'password.required'             => trans('auth.passwordRequired'),
                'password.min'                  => trans('auth.PasswordMin'),
                'password.max'                  => trans('auth.PasswordMax'),
                //'g-recaptcha-response.required' => trans('auth.captchaRequire'),
                //'captcha.min'                   => trans('auth.CaptchaWrong'),
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $ipAddress = new CaptureIpTrait();
        $role = Role::where('slug', '=', 'unverified')->first();

        $user = User::create([
                'name'              => $data['name'],
                'first_name'        => $data['first_name'],
                'last_name'         => $data['last_name'],
                'email'             => $data['email'],
                'password'          => bcrypt($data['password']),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress->getClientIp(),
                'activated'         => true,
            ]);
        
        $user->attachRole($role);
        $this->initiateEmailActivation($user);
        $this->createOwncloudAccount($data['email'],$data['password']);

        return $user;
    }

    private function createOwncloudAccount($email,$password){
        $client = new Client(); 
        // Desenvolvimento: 
        //$result = $client->post('http://admin:grupo2ngts@10.20.228.65/owncloud/ocs/v1.php/cloud/users', [
        // Produção:
        $result = $client->post('https://admin:grupo2ngts@ngts.ddns.net/owncloud/ocs/v1.php/cloud/users', [
        //$result = $client->post('http://admin:grupo2ngts@localhost/owncloud/ocs/v1.php/cloud/users', [
            'form_params' => [
            'userid' => $email,
            'password' => $password,
        ]
        ]);
        if ( $result->getStatusCode() ) {
            
        } else {
            
        }
    }
}
