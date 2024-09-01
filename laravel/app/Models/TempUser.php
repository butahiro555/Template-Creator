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
	    'expires_at', // 追加
    ];

    public static function createOrUpdateTempUser($email)
    {
        $verificationCode = Str::upper(Str::random(5)); // 認証コードを生成
        $verificationCodeExpiresAt = Carbon::now()->addHours(5); // 認証コードの有効期限を5時間後に設定
        $token = Str::random(32); // トークンを生成
        $tokenExpiresAt = Carbon::now()->addHours(5); // トークンの有効期限を5時間後に設定
	    $expiresAt = Carbon::now()->addHours(1); // 1時間後に設定

        // 条件付き挿入または更新
        return self::updateOrCreate(
            ['email' => $email], // 検索条件
            [
                'verification_code' => $verificationCode,
                'verification_code_expires_at' => $verificationCodeExpiresAt,
                'token' => $token,
		        'token_expires_at' => $tokenExpiresAt,
		        'expires_at' => $expiresAt,
            ] // 更新または作成する値
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

