<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class TokenService
{
    // 5桁の大文字の認証コードを生成
    public static function generateVerificationCode($length = 5)
    {
        return Str::upper(Str::random($length));
    }

    // 32桁のトークンを生成
    public static function generateToken($length = 32)
    {
        return Str::random($length);
    }

    // 有効期限を5分間に設定
    public static function calculateExpiry($minutes = 5)
    {
        return Carbon::now()->addminutes($minutes);
    }
}
