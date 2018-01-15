<?php

namespace App\Traits;

use Illuminate\Support\Facades\Input;
use ReCaptcha\ReCaptcha;

trait CaptchaTrait
{
    public function captchaCheck()
    {
        $response = Input::get('g-recaptcha-response');
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $secret = "6Lc31kAUAAAAAPjQ0GP1ZILxn6AZrewJNkzaE1UM";

        $recaptcha = new ReCaptcha($secret);
        $resp = $recaptcha->verify($response, $remoteip);
        
        if ($resp->isSuccess()) {
            //dd($resp);
            return true;
        }
        // prodution put on false
        return false;
    }
}
