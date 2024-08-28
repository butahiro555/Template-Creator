<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class TempUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'verification_code',
        'verification_code_expires_at',
        'token',
        'token_expires_at', // ここにtoken_expires_atを追加
    ];

    public static function createTempUser($email)
    {
        $verificationCode = Str::upper(Str::random(5)); // 5桁の英数字コードを生成
        $verificationCodeExpiresAt = Carbon::now()->addhours(5); // 現在時刻から5分後に設定
        $token = Str::random(32); // トークンを生成
        $tokenExpiresAt = Carbon::now()->addhours(5); // トークンの有効期限を5分後に設定
    
        return self::create([
            'email' => $email,
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $verificationCodeExpiresAt,
            'token' => $token,
            'token_expires_at' => $tokenExpiresAt,
        ]);
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
