<?php

namespace App\Http\Controllers;

use App\Models\TempUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\VerificationEmail;

class TempUsersController extends Controller
{
    // 仮ユーザー登録画面を表示
    public function create()
    {
        return view('auth.temp-register');
    }

    // 仮ユーザー情報入力、送信
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:temp_users,email'],
        ]);

        // 仮ユーザーを作成
        $tempUser = TempUser::createTempUser($request->email);

        // 認証コードとトークンの取得
        $verificationCode = $tempUser->verification_code;
        $token = $tempUser->token;

        // 確認メール用のURLを生成
        $verificationUrl = route('register.show', ['token' => $token]);

        // セッションに認証コードとトークンを保存
        session(['verification_code' => $verificationCode, 'email' => $request->email, 'token' => $token]);

        // メール送信
        Mail::to($request->email)->send(new VerificationEmail($verificationUrl, $verificationCode));

        return view('auth.verify-email');
    }
}
