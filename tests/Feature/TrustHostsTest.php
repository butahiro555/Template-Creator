<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Middleware\TrustHosts;

class TrustHostsTest extends TestCase
{
    // 信頼されるホストが正しいことをテスト
    public function testTrustedHosts()
    {
        // 設定されているURLを取得（http://localhost）
        $appUrl = parse_url(config('app.url'), PHP_URL_HOST); // 'localhost' だけを取得

        // TrustHosts ミドルウェアをインスタンス化
        $middleware = new TrustHosts(app());

        // TrustHosts ミドルウェアが返す信頼されたホストを取得
        $trustedHosts = $middleware->hosts();

        // 正規表現でホストが信頼されているか確認
        $isTrusted = false;
        foreach ($trustedHosts as $pattern) {
            if (preg_match("#^$pattern$#", $appUrl)) {
                $isTrusted = true;
                break;
            }
        }

        // アプリケーションのホスト（例: localhost）が信頼されたホストに含まれていることを確認
        $this->assertTrue($isTrusted);
    }

    // 信頼されていないホストが含まれていないことをテストします。
    public function testUntrustedHosts()
    {
        // 信頼されていないホストを設定
        $untrustedHost = 'untrusted.example.com';

        // TrustHosts ミドルウェアインスタンスを作成
        $middleware = new TrustHosts(app());

        // TrustHosts ミドルウェアによって信頼されているホストを取得
        $trustedHosts = $middleware->hosts();

        // 正規表現で信頼されていないホストがリストに含まれていないことを確認
        $isUntrusted = true;
        foreach ($trustedHosts as $pattern) {
            if (preg_match("#^$pattern$#", $untrustedHost)) {
                $isUntrusted = false;
                break;
            }
        }

        // 信頼されていないホストがリストに含まれていないことを確認
        $this->assertTrue($isUntrusted);
    }
}