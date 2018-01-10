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
    public $alarm_id;
    public $date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$alarm_id,$date)
    {
        $this->user = $user;
        $this->alarm_id = $alarm_id;
        $this->date = $date;
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
