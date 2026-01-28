<?php

namespace App\Mail;

use App\Models\HRActivityRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HRActivityRequestCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public HRActivityRequest $request) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New HR Activity Request: '.$this->request->activity_type->value,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hr-activity-request-created',
            with: [
                'request' => $this->request,
                'employee' => $this->request->employee,
                'supervisor' => $this->request->supervisor,
            ],
        );
    }
}
