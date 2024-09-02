<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Services\TokenService; // トークンサービスをインポート

class TempUser extends Model
{
    use HasFactory;

    protected $fillable = [

        'email', // メールアドレス
        'verification_code', // 認証コード有効期限
        'verification_code_expires_at', // 認証コード有効期限
        'token', // トークンコード
        'token_expires_at', // トークン有効期限
	    'expires_at', // 有効期間追加
        'resend_count', // 再送信カウント
    ];

    // TokenServiceクラスを利用
    public static function createOrUpdateTempUser($email)
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
