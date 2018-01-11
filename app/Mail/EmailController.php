<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailController extends Mailable
{
    use Queueable, SerializesModels;



    public $user;
    public $device;
    public $date;
    public $motion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$device,$date,$motion)
    {
        $this->user = $user;
        $this->device = $device;
        $this->date = $date;
        $this->motion = $motion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Intruder Detected')->markdown('emails.email');
    }
}
