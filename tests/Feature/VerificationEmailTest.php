<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerificationEmailTest extends TestCase
{
    use RefreshDatabase;

    public function testVerificationEmail()
    {
        // ダミーのURLとコードをセット
        $verificationUrl = 'https://example.com/verify';
        $verificationCode = '12345';

        // VerificationEmail インスタンスを作成
        $mailable = new VerificationEmail($verificationUrl, $verificationCode);

        // 件名の確認
        $this->assertEquals(
            '[Template Creator]仮ユーザー登録における、メールアドレスの確認のお知らせ',
            $mailable->envelope()->subject
        );

        // ビューの確認とビューに渡されるデータの確認
        $content = $mailable->content();

        $this->assertEquals('emails.verification-email', $content->view);
        $this->assertArrayHasKey('verificationUrl', $content->with);
        $this->assertArrayHasKey('verificationCode', $content->with);
        $this->assertEquals($verificationUrl, $content->with['verificationUrl']);
        $this->assertEquals($verificationCode, $content->with['verificationCode']);
    }
}