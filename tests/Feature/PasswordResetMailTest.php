<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordResetMailTest extends TestCase
{
    use RefreshDatabase;

    public function testPasswordResetMail(): void
    {
        // ダミーのURLとコードをセット
        $verificationUrl = 'https://example.com/verify';
        $verificationCode = '12345';

        // PasswordResetMail インスタンスを作成
        $mailable = new PasswordResetMail($verificationUrl, $verificationCode);

        // 件名の確認
        $this->assertEquals(
            '[Template Creator]パスワードリセット', 
            $mailable->envelope()->subject
        );

        // ビューの確認とビューに渡されるデータの確認
        $content = $mailable->content();

        $this->assertEquals('emails.password-reset', $content->view);
        $this->assertArrayHasKey('verificationUrl', $content->with);
        $this->assertArrayHasKey('verificationCode', $content->with);
        $this->assertEquals($verificationUrl, $content->with['verificationUrl']);
        $this->assertEquals($verificationCode, $content->with['verificationCode']);
    }
}