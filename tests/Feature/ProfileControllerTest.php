<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    
     // テスト用のプレーンテキストパスワード
    protected $plainPassword = 'current_password';

    public function setUp(): void
    {
        parent::setUp();

        // ユーザーをプレーンテキストのパスワードで生成
        $this->user = User::factory()->create([
            'password' => Hash::make($this->plainPassword),
        ]);
        
        // ユーザーを認証
        $this->actingAs($this->user);
    }

    // プロフィール画面を表示するテスト
    public function testShowProfile()
    {
        $response = $this->get(route('profile.index'));
        $response->assertStatus(200);
    }

    // ユーザー名変更フォームを表示するテスト
    public function testShowEditNameForm()
    {
        $response = $this->get(route('profile.edit-name'));
        $response->assertStatus(200);
    }

    // ユーザー名を変更するテスト
    public function testUpdateNameSend()
    {
        $response = $this->patch(route('profile.update-name', ['name' => 'New_name']));
        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas(['status' => trans('success_message.name_change_successful')]);
    }

    // 同じユーザー名を入力して送信した場合のテスト
    public function testUpdateSameNameSend()
    {
        $response = $this->patch(route('profile.update-name', ['name' => $this->user->name]));
        $response->assertSessionHasErrors(['name' => trans('error_message.name_not_change')]);
    }

    // 現パスワード入力フォームの表示のテスト
    public function testConfirmCurrentPasswordForm()
    {
        $response = $this->get(route('profile.password-confirm-form'));
        $response->assertStatus(200);
    }

    // 現パスワードの確認処理
    public function testConfirmCurrentPassword()
    {
        // 正しいパスワードを送信
        $response = $this->post(route('profile.password-confirm', ['password' => $this->plainPassword]));
        $response->assertRedirect(route('profile.edit-password'));
    }

    // 現パスワードを間違えたときの処理
    public function testConfirmCurrentPasswordFailed()
    {
        // 間違ったパスワードを送信
        $response = $this->post(route('profile.password-confirm', ['password' => 'wrong_password']));
        $response->assertSessionHasErrors(['password' => trans('error_message.password_is_wrong')]);
    }

    // パスワード変更フォームを表示するテスト
    public function testShowEditPasswordForm()
    {
        $response = $this->get(route('profile.edit-password'));
        $response->assertStatus(200);
    }

    // パスワード変更処理のテスト
    public function testUpdatePasswordSend()
    {
        // 新しいパスワードを設定
        $newPassword = 'new_password';

        // 正しいプレーンテキストの新パスワードを送信
        $response = $this->patch(route('profile.update-password', [
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]));

        // リダイレクトとセッションメッセージの確認
        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas(['status' => trans('success_message.password_change_successful')]);
    }

    // 変更するパスワードと、その確認パスワードが一致しない場合のエラーメッセージを出力するテスト
    public function testUpdatePasswordSendFailed()
    {
        // 新しいパスワードと一致しない確認パスワードを送信
        $response = $this->patch(route('profile.update-password', [
            'password' => 'new_password',
            'password_confirmation' => 'old_password',
        ]));

        // エラーメッセージを確認
        $response->assertSessionHasErrors(['password' => trans('error_message.confirmed')]);
    }

    // 元のパスワードのままのときのエラーメッセージを出力するテスト
    public function testNotUpdatePasswordSend()
    {
        // 既存のパスワードと同じパスワードを送信
        $response = $this->patch(route('profile.update-password', [
            'password' => $this->plainPassword,
            'password_confirmation' => $this->plainPassword,
        ]));

        // 期待するエラーメッセージがセッションに格納されているか確認
        $response->assertSessionHasErrors(['password' => trans('error_message.password_not_change')]);
    }

    // 退会希望者の現パスワード入力フォームを表示するテスト

    public function testConfirmCurrentPasswordFormForDeleteUser()
    {
        $response = $this->get(route('profile.delete-user-password-confirm-form'));

        $response->assertStatus(200);
    }

    // 退会希望者の現パスワードを確認するテスト
    public function testConfirmCurrentPasswordForDeleteUser()
    {
        // 正しいパスワードを送信
        $response = $this->post(route('profile.delete-user-password-confirm-form', ['password' => $this->plainPassword]));
        $response->assertRedirect(route('profile.delete-user-form'));
    }

    // 現パスワードを間違えたときのテストは、81行目から87行目に記述済みのため省略

    // 退会確認画面を表示するテスト
    public function testDeleteUserForm()
    {
        $response = $this->get(route('profile.delete-user-form'));

        $response->assertStatus(200);
    }

    // 退会処理のテスト
    public function testDeleteUser()
    {
        $response = $this->delete(route('profile.delete-user-send'));

        $response->assertRedirect(route('goodbye'));
    }
}