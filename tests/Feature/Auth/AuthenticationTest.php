<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Http\Middleware\Authenticate;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $plainPassword = 'current_password';

    public function setUp(): void
    {
        parent::setUp();
        
        // ユーザーをプレーンテキストのパスワードで生成
        $this->user = User::factory()->create([
            'password' => Hash::make($this->plainPassword),
        ]);

        // CSRFトークン検証を無効にする
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    }

    // ログインページを表示するテスト
    public function testShowLoginForm()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    // ログインのテスト
    public function testLogin(): void
    {
        $response = $this->post(route('login', [
            'email' => $this->user->email,
            'password' => $this->plainPassword,
        ]));

        $response->assertRedirect(route('templates.index'));
    }

    // ログイン時、パスワードが間違えたときの処理のテスト
    public function testLoginWithWrongPassword()
    {
        $response = $this->post(route('login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]));

        $response->assertSessionHasErrors(['email' => trans('error_message.email_invalid')]);
    }

    // 認証されていないユーザーが /templates にアクセスした場合のテスト.
    public function testGuestUserCannotAccessTemplates()
    {
        // 認証されていない状態で /templates にアクセス
        $response = $this->get(route('templates.show'));

        // ログインページへリダイレクトされることを確認
        $response->assertRedirect(route('login'));
    }

    // 認証およびメール認証済みのユーザーが /templates にアクセスできることを確認.
    public function testVerifiedUserCanAccessTemplates()
    {
        // 認証済みかつメール認証済み状態で /templates にアクセス
        $response = $this->actingAs($this->user)->get(route('templates.show'));

        // 正常なレスポンスが返されることを確認
        $response->assertStatus(200);
    }

    // ログアウト処理のテスト
    public function testLogout(): void
    {
        // ユーザーを認証
        $this->actingAs($this->user);

        // ログアウトリクエスト
        $response = $this->post(route('logout'));

        // リダイレクトの確認
        $response->assertRedirect(route('templates.index'));
        
        // ユーザーがゲスト状態であることを確認
        $this->assertGuest();
    }
}