<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TempUser;
use Carbon\Carbon;

class TempUserTest extends TestCase
{
    protected $tempUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // モックまたはテスト用モデルを用意
        $this->tempUser = $this->getMockBuilder(TempUser::class)
            ->onlyMethods(['update'])
            ->getMock();

        // デフォルト値を設定
        $this->tempUser->verification_code = '12345';
        $this->tempUser->verification_code_expires_at = Carbon::now()->addMinutes(10);
    }

    public function testIsVerificationCodeValid()
    {
        // 有効なコードをテスト
        $this->assertTrue($this->tempUser->isVerificationCodeValid('12345'));

        // 無効なコードをテスト
        $this->assertFalse($this->tempUser->isVerificationCodeValid('54321'));

        // 有効期限が過ぎた場合のテスト
        $this->tempUser->verification_code_expires_at = Carbon::now()->subMinutes(10);
        $this->assertFalse($this->tempUser->isVerificationCodeValid('12345'));
    }

    public function testInvalidateVerificationCode()
    {
        // `update` メソッドが呼ばれることを確認
        $this->tempUser->expects($this->once())
            ->method('update')
            ->with([
                'verification_code' => null,
                'verification_code_expires_at' => null,
            ]);

        // メソッド呼び出し
        $this->tempUser->invalidateVerificationCode();
    }
}