<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobBulkMail extends Mailable
{
    use Queueable, SerializesModels;

    private $mailMessage;
    public function __construct($mailMessage)
    {
        $this->mailMessage = $mailMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Job Bulk Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.emails.custom_message',
            with: [
                'mailMessage'=>$this->mailMessage
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     $attachments = [];

    //     if (!empty($this->mailMessage['attachments']) && is_array($this->mailMessage['attachments'])) {
    //         foreach ($this->mailMessage['attachments'] as $filePath) {
    //             if (file_exists($filePath)) {
    //                 $attachments[] = Attachment::fromPath($filePath)
    //                     ->as(basename($filePath)); // Optional: name in email
    //             }
    //         }
    //     }

    //     return $attachments;
    // }
}
