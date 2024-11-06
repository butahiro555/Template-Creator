<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

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

    public function testShowLoginForm()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function testLogin(): void
    {
        $response = $this->post(route('login', [
            'email' => $this->user->email,
            'password' => $this->plainPassword,
        ]));

        $response->assertRedirect(route('templates.index'));
    }

    public function testLoginWithWrongPassword()
    {
        $response = $this->post(route('login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]));

        $response->assertSessionHasErrors(['email' => trans('error_message.email_invalid')]);
    }

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