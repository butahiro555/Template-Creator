<?php

namespace App\Http\Controllers;

use App\Models\TempUser;
use App\Models\User;
use App\Mail\VerificationEmail;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class TempUsersController extends Controller
{   
    // 初期化するための変数を準備
    protected $validationsController;

    // コンストラクタで ValidationsController のインスタンスを受け取り、プロパティに代入
    public function __construct(ValidationsController $validationsController)
    {
        // 受け取った ValidationsController インスタンスをプロパティに設定
        $this->validationsController = $validationsController;
    }

    // メール認証送信画面を表示
    public function tempRegister()
    {
        return view('auth.temp-register');
    }

    // メール認証送信
    public function sendVerificationEmail(Request $request)
    {
        // メールアドレスのバリデーション
        $this->validationsController->validateEmail($request);
        $email = $request->email;

        // 登録済みユーザーであるかをチェック
        if (User::where('email', $email)->exists()) {
            return redirect()->back()->withErrors(['email' => trans('error_message.user_already_registered')]);
        }

        // 再送信回数をセッションから取得し、上限をチェック
        $resendCount = session()->get("resend_count_{$email}", 0);
        if ($resendCount >= 3) {
            return redirect()->back()->withErrors(['email' => trans('error_message.resend_limit')]);
        }

        // 仮ユーザー情報のバリデーションチェックおよび、メール送信
        $tempUser = TempUser::createOrUpdateTempUser($email);
        $verificationCode = $tempUser->verification_code;
        $token = $tempUser->token;
        $verificationUrl = route('register.show', ['token' => $token]);

        // 再送信回数をインクリメントし、セッションに保存
        session()->put("resend_count_{$email}", $resendCount + 1);

        // メール送信
        Mail::to($email)->queue(new VerificationEmail($verificationUrl, $verificationCode));

        // メール確認を促すページにリダイレクト
        return redirect()->route('verify-your-email');
    }
}
