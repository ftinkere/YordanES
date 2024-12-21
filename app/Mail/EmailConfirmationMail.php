<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class EmailConfirmationMail extends Mailable
{
    public function __construct(
        public string $userName,
        public string $link
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Подтверждение почты Ëрдан',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.email-confirmation',
        )->with([
            'name' => $this->userName,
            'link' => $this->link,
        ]);
    }
}