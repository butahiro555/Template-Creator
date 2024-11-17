<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ForgotPasswordUser;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordResetCompleted;
use Carbon\Carbon;

class ForgotPasswordUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // 登録済みユーザーとパスワード再設定用インスタンスを準備
        $this->user = User::factory()->create();

        // CSRFトークン検証を無効にする
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    }

    // パスワード再設定画面を表示するテスト
    public function testForgotPasswordForm(): void
    {
        $response = $this->get(route('forgot-password.index'));

        $response->assertStatus(200);
    }

    // 登録済みでなはないユーザー向けのエラーメッセージの出力テスト
    public function testSendResetPasswordEmailForNotRegisteredUser(): void
    {
        // 登録していないユーザーのメールアドレスでリクエストを送信
        $response = $this->post(route('forgot-password.send'), ['email' => 'test@example.com']);

        // エラーメッセージの検証
        $response->assertSessionHasErrors(['email' => trans('error_message.user_not_found')]);
    }

    // 登録済みのユーザーがパスワード再設定メールを初めて送信する場合のテスト
    public function testSendResetForgotPasswordUser()
    {
        $email = $this->user->email;

        Mail::fake();

        // ユーザーが作成されたかの確認
        $this->assertDatabaseMissing('forgot_password_users', ['email' => $email]);

        // リクエスト送信
        $response = $this->post(route('forgot-password.send'), ['email' => $email]);

        // 新規作成されたことを確認
        $forgotPasswordUser = ForgotPasswordUser::where('email', $email)->first();
        $this->assertNotNull($forgotPasswordUser);

        // メールが送信されたかを確認し、内容を検証
        $verificationUrl = route('forgot-password.resetform', ['token' => $forgotPasswordUser->token]);
        Mail::assertQueued(PasswordResetMail::class, function ($mail) use ($email, $forgotPasswordUser, $verificationUrl) {
            return $mail->hasTo($email) &&
                $mail->verificationCode === $forgotPasswordUser->verification_code &&
                $mail->verificationUrl === $verificationUrl;
        });

        // レスポンスのリダイレクト先を確認
        $response->assertRedirect(route('verify-your-email'));
    }

    // 登録済みユーザーがパスワード再設定メールを再送信する場合のテスト 
    public function testResendResetForgotPasswordUser()
    {
        $email = $this->user->email;
        $this->forgotPasswordUser = ForgotPasswordUser::factory()->create([
            'email' => $this->user->email,
        ]);

        Mail::fake();

        // リクエスト送信
        $response = $this->post(route('forgot-password.send'), ['email' => $email]);

        // 既存のForgotPasswordUserを再度取得
        $forgotPasswordUser = ForgotPasswordUser::where('email', $email)->first();
        $this->assertNotNull($forgotPasswordUser);

        // メールが送信されたかを確認し、内容を検証
        $verificationUrl = route('forgot-password.resetform', ['token' => $forgotPasswordUser->token]);
        Mail::assertQueued(PasswordResetMail::class, function ($mail) use ($email, $forgotPasswordUser, $verificationUrl) {
            return $mail->hasTo($email) &&
                $mail->verificationCode === $forgotPasswordUser->verification_code &&
                $mail->verificationUrl === $verificationUrl;
        });

        // レスポンスのリダイレクト先を確認
        $response->assertRedirect(route('verify-your-email'));
    }

    // 再送信回数が上限の3回に達していた場合のエラーメッセージを出力するテスト
    public function testResendLimit()
    {
        $email = $this->user->email;
        $this->forgotPasswordUser = ForgotPasswordUser::factory()->create([
            'email' => $this->user->email,
            'resend_count' => 3,
        ]);

        // リクエスト送信
        $response = $this->post(route('forgot-password.send'), ['email' => $email]);

        // エラーメッセージを検証
        $response->assertSessionHasErrors(['email' => trans('error_message.resend_limit')]);
    }

    // error_message.unexpected_errorを表示するテスト
    public function testUnexpectedErrorMessage()
    {
        $email = $this->user->email;
        
        Mail::fake();
        
        // メール送信時に例外を発生させるモック
        Mail::shouldReceive('to->queue')->andThrow(new \Exception('Mocked Exception'));

        $response = $this->post(route('forgot-password.send'), ['email' => $email]);

        $response->assertSessionHasErrors(['email' => trans('error_message.unexpected_error')]);
    }

    // パスワードリセットフォームを表示するテスト
    public function testShowResetForm()
    {
        // ForgotPasswordUserを作成
        $forgotPasswordUser = ForgotPasswordUser::factory()->create();

        // トークンの有効期限を5分後に設定
        $forgotPasswordUser->update(['token_expires_at' => now()->addMinutes(5)]);

        // リクエストを送信
        $response = $this->get(route('forgot-password.resetform', ['token' => $forgotPasswordUser->token]));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);
    }

    // トークンの有効期限が無効の場合のエラーメッセージを出力するテスト
    public function testShowResetFormTokenOverExpires()
    {
        // ForgotPasswordUserを作成
        $forgotPasswordUser = ForgotPasswordUser::factory()->create();

        // トークンの有効期限を5分前に設定
        $forgotPasswordUser->update(['token_expires_at' => now()->subMinutes(5)]);

        // リクエストを送信
        $response = $this->get(route('forgot-password.resetform', ['token' => $forgotPasswordUser->token]));

        // 有効期限が切れている旨のエラーメッセージを表示
        $response->assertSessionHasErrors(['expired' => trans('error_message.token_expired')]);
    }

    // パスワードリセットを検証するテスト
    public function testPasswordReset()
    {
        $email = $this->user->email;

        // ForgotPasswordUserを作成
        $forgotPasswordUser = ForgotPasswordUser::factory()->create([
            'email' => $email,
        ]);

        Mail::fake();

        $response = $this->post(route('forgot-password.reset'), [
            'email' => $forgotPasswordUser->email,
            'verification_code' => $forgotPasswordUser->verification_code,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // キュー経由で送信されたことを確認
        Mail::assertQueued(PasswordResetCompleted::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });

        // レスポンスのリダイレクト先を確認
        $response->assertRedirect(route('login'));
        $response->assertSessionHas(['status' => trans('success_message.password_reset_successful')]);
    }

    // パスワードリセット失敗時のエラーメッセージを出力するテスト
    public function testPasswordResetFailed()
    {
        $email = $this->user->email;

        // ForgotPasswordUserを作成
        $forgotPasswordUser = ForgotPasswordUser::factory()->create([
            'email' => $email,
        ]);

        $response = $this->post(route('forgot-password.reset'), [
            'email' => $forgotPasswordUser->email,
            'verification_code' => 'ABCDE', // 認証コードを間違えておく
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        //エラーメッセージの検証
        $response->assertSessionHasErrors(['error' => trans('error_message.password_reset_fail')]);
    }
}