<?php

namespace App\Http\Controllers;

use App\Models\TempUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\VerificationEmail;
use App\Mail\RegistrationCompleted;

class UsersController extends Controller
{
    // 本登録ページを表示するメソッド
    public function showRegistrationForm(Request $request)
    {
        $token = $request->query('token');

        // トークンが有効であるか確認
        $tempUser = TempUser::where('token', $token)
            ->where('token_expires_at', '>', Carbon::now())
            ->firstOrFail();

        // トークンとメールアドレスをビューに渡す
        return view('auth.register', [
            'email' => $tempUser->email,
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'verification_code' => ['required', 'string', 'size:5'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        // トークンと認証コードを検証
        $tempUser = TempUser::where('email', $request->email)
            ->where('verification_code', $request->verification_code)
            ->where('verification_code_expires_at', '>', Carbon::now())
            ->firstOrFail();

        // 本登録ユーザーを作成
        $user = User::create([
            'name' => $request->name,
            'email' => $tempUser->email,
            'password' => Hash::make($request->password),
        ]);

        // 仮ユーザーのデータを削除
        $tempUser->delete();

        // 本登録完了メールを送信
        Mail::to($tempUser->email)->send(new RegistrationCompleted());

        // ログイン画面にリダイレクト
        return redirect()->route('login')->with('status', 'Registration completed. You can now log in.');
    }
}
