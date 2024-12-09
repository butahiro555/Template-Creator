<?php

namespace Tests\Unit\View\Components;

use App\View\Components\AppLayout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class AppLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function testRenderMethodReturnsCorrectView(): void
    {
        // コンポーネントをインスタンス化
        $component = new AppLayout();

        // renderメソッドを実行し、結果が期待どおりかを確認
        $view = $component->render();

        // ビュー名が 'layouts.app' であることを確認
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
        $this->assertEquals('layouts.app', $view->name());
    }
}
