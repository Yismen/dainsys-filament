<?php

namespace App\Mail;

use App\Models\HRActivityRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HRActivityRequestCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public HRActivityRequest $request,
        public string $comment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'HR Activity Request Completed: '.$this->request->activity_type->value,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hr-activity-request-completed',
            with: [
                'request' => $this->request,
                'comment' => $this->comment,
                'employee' => $this->request->employee,
                'supervisor' => $this->request->supervisor,
            ],
        );
    }
}
