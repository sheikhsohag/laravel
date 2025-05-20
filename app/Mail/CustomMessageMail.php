<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomMessageMail extends Mailable
{
    use Queueable, SerializesModels;
    public $message;
    /**
     * Create a new message instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
    public function build()
    {
        return $this->subject('Custom Message From My Application')
                    ->cc('shekhsohag000@gmail.com')
                    ->view('emails.custom_message',[
                        "messageContent" =>$this->message
                    ]);
    }

    // public function build()
    // {
    //     return $this->subject($this->subject ?? 'Custom Message From My Application')
    //                 ->view('emails.custom_message')
    //                 ->cc(...$this->cc)  // Spread operator for array
    //                 ->bcc(...$this->bcc) // Spread operator for array
    //                 ->with([
    //                     "messageContent" => $this->message
    //                 ]);
    // }

}
