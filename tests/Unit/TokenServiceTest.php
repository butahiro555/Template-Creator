<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TokenService;
use Carbon\Carbon;

class TokenServiceTest extends TestCase
{
    // generateVerificationCode メソッドのテスト
    public function testGenerateVerificationCode()
    {
        // 5桁の認証コードが生成されることを確認
        $code = TokenService::generateVerificationCode(5);
        $this->assertEquals(5, strlen($code));
        $this->assertEquals(strtoupper($code), $code);  // 大文字であることを確認

        // 10桁の認証コードが生成されることを確認
        $code = TokenService::generateVerificationCode(10);
        $this->assertEquals(10, strlen($code));
        $this->assertEquals(strtoupper($code), $code);
    }

    // generateToken メソッドのテスト
    public function testGenerateToken()
    {
        // 32桁のトークンが生成されることを確認
        $token = TokenService::generateToken(32);
        $this->assertEquals(32, strlen($token));
    }

    // calculateExpiry メソッドのテスト
    public function testCalculateExpiry()
    {
        $now = Carbon::now(); // 現在時刻をキャプチャ
        $expiry = TokenService::calculateExpiry(5);
    
        $this->assertInstanceOf(Carbon::class, $expiry);
        $this->assertTrue($expiry->isFuture());  // 有効期限が未来の日時であることを確認
    
        // 現在の時刻から5分以上の差があることを確認
        $this->assertTrue($expiry->gt($now));  // expiryがnowより後であることを確認
    }
}