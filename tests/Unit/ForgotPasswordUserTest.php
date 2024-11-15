<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ForgotPasswordUser;
use Carbon\Carbon;

class ForgotPasswordUserTest extends TestCase
{
    protected $forgotPasswordUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // モックまたはテスト用モデルを用意
        $this->forgotPasswordUser = $this->getMockBuilder(ForgotPasswordUser::class)
            ->onlyMethods(['update'])
            ->getMock();

        // デフォルト値を設定
        $this->forgotPasswordUser->verification_code = '12345';
        $this->forgotPasswordUser->verification_code_expires_at = Carbon::now()->addMinutes(10);
    }

    public function testIsVerificationCodeValid()
    {
        // 有効なコードをテスト
        $this->assertTrue($this->forgotPasswordUser->isVerificationCodeValid('12345'));

        // 無効なコードをテスト
        $this->assertFalse($this->forgotPasswordUser->isVerificationCodeValid('54321'));

        // 有効期限が過ぎた場合のテスト
        $this->forgotPasswordUser->verification_code_expires_at = Carbon::now()->subMinutes(10);
        $this->assertFalse($this->forgotPasswordUser->isVerificationCodeValid('12345'));
    }

    public function testInvalidateVerificationCode()
    {
        // `update` メソッドが呼ばれることを確認
        $this->forgotPasswordUser->expects($this->once())
            ->method('update')
            ->with([
                'verification_code' => null,
                'verification_code_expires_at' => null,
            ]);

        // メソッド呼び出し
        $this->forgotPasswordUser->invalidateVerificationCode();
    }
}