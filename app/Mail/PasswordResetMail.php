<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;
    public $verificationCode;

    /**
     * Create a new message instance.
     */
    public function __construct($verificationUrl, $verificationCode)
    {
        $this->verificationUrl = $verificationUrl;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Template Creator]パスワードリセット',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset', // ビューの名前を指定
            with: [
                'verificationCode' => $this->verificationCode,
                'verificationUrl' => $this->verificationUrl
            ],
        );
    }
}