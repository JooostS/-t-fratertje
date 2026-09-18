<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AdministrationNotice extends Mailable
{
    public function __construct(
        public string $noticeSubject,
        public string $text,
        public string $url,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "[’t Fratertje] {$this->noticeSubject}");
    }

    public function content(): Content
    {
        return new Content(text: 'mail.administration-notice');
    }
}
