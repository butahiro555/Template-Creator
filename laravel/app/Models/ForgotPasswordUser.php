<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Services\TokenService;

class ForgotPasswordUser extends Model
{
    use HasFactory;

    protected $table = 'forgot_password_users';

    protected $fillable = [
        'email',
        'verification_code',
        'verification_code_expires_at',
        'token',
        'token_expires_at',
    ];

    // TokenServiceクラスを利用
    public static function createOrUpdateForgotPasswordUser($email)
    {
        return self::updateOrCreate(
            ['email' => $email], // 検索条件
            [
                'verification_code' => TokenService::generateVerificationCode(),
                'verification_code_expires_at' => TokenService::calculateExpiry(),
                'token' => TokenService::generateToken(),
                'token_expires_at' => TokenService::calculateExpiry(),
                'expires_at' => TokenService::calculateExpiry(60), // 仮ユーザーの有効期限のみ60分に設定
            ]
        );
    }

    public function isVerificationCodeValid($code)
    {
        return $this->verification_code === $code &&
               $this->verification_code_expires_at->isFuture();
    }

    public function invalidateVerificationCode()
    {
        $this->update([
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);
    }
}
