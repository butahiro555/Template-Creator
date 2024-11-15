<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TemplateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    // 作成したテンプレート文の内容の一致を確認するテスト
    public function testTemplateModel()
    {
        // Templateを作成
        $template = Template::create([
            'title' => 'Sample Title',
            'content' => 'This is the content of the template.',
            'user_id' => $this->user->id,
        ]);

        // タイトルの一致を確認
        $this->assertEquals('Sample Title', $template->title);

        // 文章の内容の一致を確認
        $this->assertEquals('This is the content of the template.', $template->content);

        // テンプレートを作成したユーザーのIDと、テンプレートのuser_idカラムの一致を確認
        $this->assertEquals($this->user->id, $template->user_id);

        // Templateモデルが関連付けられているUserモデルが、実際にUserクラスのインスタンスであることを確認する
        $this->assertTrue($template->user instanceof User);

        // created_at と updated_at が設定されていることを確認
        $this->assertNotNull($template->created_at);
        $this->assertNotNull($template->updated_at);
    }
}