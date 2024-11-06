<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\TempUser;
use App\Mail\RegistrationCompleted;
use Illuminate\Support\Facades\Notification;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        
        // 本登録に使用する仮ユーザーを作成し、プロパティとして保持
        $this->tempUser = TempUser::factory()->create();

        // CSRFトークン検証を無効にする
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    }

    // 有効期限内のトークンを使ってユーザー登録ページを表示するテスト
    public function testRegistrationFormDisplay()
    {
        // 有効期限を延長
        $this->tempUser->update(['token_expires_at' => now()->addMinutes(5)]);

        // トークンを使ってユーザー登録ページが表示されるかを検証
        $response = $this->get(route('register.show', ['token' => $this->tempUser->token]));

        $response->assertStatus(200);
    }

    // 有効期限外のトークンを使ってユーザー登録ページのエラーを表示するテスト
    public function testRegistrationFormErrorDisplay()
    {
        // 有効期限が5分前に失効
        $this->tempUser->update(['token_expires_at' => now()->subMinutes(5)]);

        $response = $this->get(route('register.show', ['token' => $this->tempUser->token]));

        // 有効期限が切れている旨のエラーメッセージを表示
        $response->assertSessionHasErrors(['token' => trans('error_message.token_expired')]);
    }

    // ユーザー登録処理で仮ユーザーが存在しない場合のテスト
    public function testRegistrationSendErrorDisplay()
    {
        // ユーザー登録リクエストを送信
        $response = $this->post(route('register', [
            'email' => 'test@example.com',
            'verification_code' => 'A1B2C',
            'name' => 'testUser',
            'password' => 'password',
            'password_confirmation'=> 'password',
        ]));
    
        // リダイレクトを確認
        $response->assertRedirect();
    
        // エラーメッセージの検証
        $response->assertSessionHasErrors(['tempUser' => trans('error_message.user_not_found')]);
    }

    // 認証コードの確認が一致しないときのエラーを確認するテスト
    public function testConfirmVerificationCode()
    {
        // 認証コードを'WRONG'に設定して、生成した仮ユーザー情報の認証コードと一致させないようにする
        $response = $this->post(route('register', [
            'email' => $this->tempUser->email,
            'verification_code' => 'WRONG',
            'name' => 'testUser',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));

        $response->assertRedirect();

        $response->assertSessionHasErrors(['tempUser' => trans('error_message.verification_code_incorrect')]);
    }

    // ユーザー登録処理のテスト
    public function testUserRegistrationCompletedMailSend()
    {
        $email = $this->tempUser->email;
        $this->tempUser->verification_code_expires_at = now()->addMinutes(5);
        $this->tempUser->save();

        // メール送信の偽装を準備
        Mail::fake();

        // ユーザー登録処理を実行
        $response = $this->post(route('register', [
            'email' => $email,
            'verification_code' => $this->tempUser->verification_code,
            'name' => 'testUser',
            'password' => 'password',
            'password_confirmation' => 'password',
	    ]));

	    // ユーザーがデータベースに登録されているか確認
        $this->assertDatabaseHas('users', [
            'email' => $this->tempUser->email,
            'name' => 'testUser',
        ]);
    
        // キュー経由で送信されたことを確認
        Mail::assertQueued(RegistrationCompleted::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    
        // レスポンスのリダイレクト先を確認
	    $response->assertRedirect(route('login'));
	    $response->assertSessionHas(['status' => trans('success_message.registered_successful')]);
    }
}
