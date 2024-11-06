<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Template;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplatesControllerTest extends TestCase
{   
    use RefreshDatabase;
    
    protected $user;
    protected $template;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->template = Template::factory()->create();

        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $this->actingAs($this->user);
    }

    // アプリケーションのトップページを表示するテスト
    public function testIndexScreen(): void
    {
        $response = $this->get(route('templates.show'));

        $response->assertStatus(200);
    }

    // テンプレート作成機能のテスト
    public function testCreateTemplate()
    {
        $response = $this->post(route('templates.store'), [
            'title' => 'Test Template',
            'content' => 'This is a test template content.',
        ]);

        $response->assertRedirect(route('templates.show'));
    }

    // テンプレート作成時、タイトルが制限の20文字をオーバーしたときのエラーメッセージを出力するテスト
    public function testCreateTemplateOverTitleCharacters()
    {
        $response = $this->post(route('templates.store'), [
            'title' => 'Over 20 characters title',
            'content' => 'This is a test template content.',
        ]);

        $response->assertSessionHasErrors(['title' => trans('error_message.custom.title.max')]);
    }

    // テンプレート作成時、本文が制限の191文字をオーバーしたときのエラーメッセージを出力するテスト
    public function testCreateTemplateOverContentcharacters()
    {
        $response = $this->post(route('templates.store'), [
            'title' => 'Test Template',
            'content' => Str::random(192),
        ]);

        $response->assertSessionHasErrors(['content' => trans('error_message.custom.content.max')]);
    }

    // テンプレート更新機能のテスト
    public function testUpdateTemplate()
    {
        $response = $this->patch(route('templates.update', ['id' => $this->template->id]), [
            'title' => 'Updated Title',
            'content' => 'Updated content for the template.',
        ]);

        $response->assertRedirect(route('templates.show'));
    }

    // テンプレート更新時、タイトルが制限の20文字をオーバーしたときのエラーメッセージを出力するテスト
    public function testUpdateTemplateOverTitleCharacters()
    {
        $response = $this->patch(route('templates.update', ['id' => $this->template->id]), [
            'title' => 'Over 20 characters title',
            'content' => 'Updated content for the template.',
        ]);

        $response->assertSessionHasErrors(['title' => trans('error_message.custom.title.max')]);
    }

    // テンプレート更新時、本文が制限の191文字をオーバーしたときのエラーメッセージを出力するテスト
    public function testUpdateTemplateOverContentCharacters()
    {
        $response = $this->patch(route('templates.update', ['id' => $this->template->id]), [
            'title' => 'Updated Title',
            'content' => Str::random(192),
        ]);

        $response->assertSessionHasErrors(['content' => trans('error_message.custom.content.max')]);
    }

    // テンプレート削除機能のテスト
    public function testDestroyTemplate()
    {
        $response = $this->delete(route('templates.destroy', ['id' => $this->template->id]));

        $response->assertRedirect(route('templates.show'));
    }

    // テンプレート検索機能のテスト
    public function testSearchTemplate()
    {
        // 検索リクエストを送信
        $response = $this->get(route('templates.search', [
            'keyword' => $this->template->title]));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);
        
        // 検索結果にテンプレートのタイトルが含まれていることを確認
        $response->assertSee($this->template->title);
    }

    // テンプレート検索時、該当するテンプレートが見つからないときのテスト
    public function testSearchTemplateNotFound()
    {
        // 検索リクエストを送信
        $response = $this->get(route('templates.search', [
            'keyword' => 'Not exists template']));

        // リダイレクト先でのエラーメッセージを確認
        $response->assertRedirect(route('templates.show'));
        $response->assertSessionHasErrors(['keyword' => trans('error_message.template_not_found')]);
    }
}