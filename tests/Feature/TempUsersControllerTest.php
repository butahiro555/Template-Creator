<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\TempUser;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Notification;

class TempUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $tempUser;

    public function setUp(): void
    {
        parent::setUp();

        // 仮ユーザーを作成し、プロパティとして保持
        $this->tempUser = TempUser::factory()->create();

        // CSRFトークン検証を無効にする
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    }

    // 仮ユーザー登録画面を表示するテスト
    public function testTempRegisterScreenDisplay()
    {
        $response = $this->get(route('temp-user.index'));

        $response->assertStatus(200);
    }

    // 既にユーザー登録が済んでいる場合のテスト
    public function testAlreadyRegisteredUser()
    {
        // 既存ユーザーを作成
        User::factory()->create(['email' => 'test@example.com']);
    
        // 上記で本ユーザーを作成したため、リクエストを送信
        $response = $this->post(route('temp-user.send', ['email' => 'test@example.com']));
    
        // エラーメッセージを検証
        $response->assertSessionHasErrors(['email' => trans('error_message.user_already_registered')]);
    }

    // 再送信上限回数に達していた場合のテスト
    public function testResendLimitExceed()
    {
        // セッションに再送信回数を設定
        Session::put("resend_count_{$this->tempUser->email}", 3);
    
        // 仮ユーザー情報を送信するメソッドにHTTPリクエストを送信
        $response = $this->post(route('temp-user.send', ['email' => $this->tempUser->email]));

        // エラーメッセージを検証
        $response->assertSessionHasErrors(['email' => trans('error_message.resend_limit')]);
    }

    // 仮登録ユーザーへ認証メールを送信するテスト
    public function testSendVerificationMail()
    {
        // メール送信のテストをするための偽装を準備
        Mail::fake();

        // 再送信回数0回をセッションに保存し、制限にかからないようにする
        Session::put("resend_count_{$this->tempUser->email}", 0);

        // HTTPリクエストを送信
        $response = $this->post(route('temp-user.send', ['email' => $this->tempUser->email]));

        // 仮ユーザーの検証
        $findTempUser = TempUser::where('email', $this->tempUser->email)->first();
        $this->assertEquals($this->tempUser->email, $findTempUser->email);

        // メールの送信後、確認用のURLを生成
        $verificationUrl = route('register.show', ['token' => $findTempUser->token]);

        // メールが送信されたことを検証
        Mail::assertQueued(VerificationEmail::class, function ($mail) use ($findTempUser, $verificationUrl) {
                return $mail->hasTo($findTempUser->email) &&
                       $mail->verificationCode === $findTempUser->verification_code &&
		               $mail->verificationUrl === $verificationUrl;
	    });

        // レスポンスのリダイレクト先を確認
        $response->assertRedirect(route('verify-your-email'));
    }
}