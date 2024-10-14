<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;
    public $verificationCode;


    public function __construct($verificationUrl, $verificationCode)
    {
        $this->verificationUrl = $verificationUrl;
        $this->verificationCode = $verificationCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Template Creator]仮ユーザー登録における、メールアドレスの確認のお知らせ',
        );
    }

    // ビューにデータを渡す
    public function content(): Content
    {
        return new Content(
            view: 'emails.verification-email', // ビューの名前を指定
            with: [
                'verificationCode' => $this->verificationCode,
                'verificationUrl' => $this->verificationUrl
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
