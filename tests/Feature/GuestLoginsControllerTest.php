<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class GuestLoginsControllerTest extends TestCase
{   
    use RefreshDatabase;

    protected $seedUser;

    public function setUp(): void
    {
        parent::setUp();

        // シードユーザーを作成
        $this->seedUser = User::factory()->create(['email' => 'guestuser@example.com']);
    }

    public function testGuestLogin(): void
    {
        // 設定したルートにHTTPリクエストを送信
        $response = $this->post(route('guest-login'));

        // リダイレクトが行われたことを確認
        $response->assertRedirect('/');

        // リダイレクト先のページを手動でリクエストして、そのレスポンスを確認
        $response = $this->get('/');

        // リダイレクト後に 200 ステータスコードが返されることを確認
        $response->assertStatus(200);
    }
}
