<?php

namespace App\Http\Controllers;

use App\Models\TempUser;
use App\Models\User;
use App\Mail\VerificationEmail;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
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

    // sendアクションと、resendアクションの処理の分岐
    public function handleRequest(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'send_verification':
                return $this->send($request);
            case 'resend_verification':
                return $this->resend($request);
            default:
                return redirect()->back()->withErrors(['action' => trans('error_message.unexpected_error')]);
        }
    }

// メール認証送信
    public function send(Request $request)
    {
        // ValidationsController の validateEmail メソッドを呼び出して、メールアドレスのバリデーションを実行
        $this->validationsController->validateEmail($request);

        // 入力されたメールアドレスが、登録済みかを確認する
        $checkRegisteredTempUsers = TempUser::where('email', $request->email)->first();
        $checkRegisteredUsers = User::where('email', $request->email)->first();

        // 認証送信済みかをチェック
        if ($checkRegisteredTempUsers) {
            return redirect()->back()->withErrors(['email' => trans('error_message.confirm_or_resend_your_email')]);
        }
        
        // ユーザー登録済みかをチェック
        if ($checkRegisteredUsers) {
            return redirect()->back()->withErrors(['email' => trans('error_message.user_already_registered')]);
        }

        // 最下部のメソッドを呼び出し
        return $this->processVerification($request);
    }

    // メール認証再送信
    public function resend(Request $request)
    {
        $this->validationsController->validateEmail($request);
	
	    // 仮ユーザーを検索
        $tempUser = TempUser::where('email', $request->email)->first();

        // 仮ユーザー登録済みかをチェック
        if (!$tempUser) {
            return redirect()->back()->withErrors(['email' => trans('error_message.user_not_found')]);
        }

        // 再送信回数をチェック
        if ($tempUser->resend_count < 5) {
            // 再送信回数を増やす
            $tempUser->resend_count += 1;
            $tempUser->save();

            return $this->processVerification($request);
        } else {
            return redirect()->back()->withErrors(['email' => trans('error_message.resend_limit')]);
        }
    }

    // 仮ユーザー情報のバリデーションチェックおよび、メール送信
    protected function processVerification(Request $request)
    {   
        // メールアドレス宛に認証コードとトークンを発行
        $tempUser = TempUser::createOrUpdateTempUser($request->email);
        $verificationCode = $tempUser->verification_code;
        $token = $tempUser->token;
        $verificationUrl = route('register.show', ['token' => $token]);

        // セッションに認証コードと、トークンを保存
        session(['verification_code' => $verificationCode, 'email' => $request->email, 'token' => $token]);

        // メール送信
        Mail::to($request->email)->send(new VerificationEmail($verificationUrl, $verificationCode));

        // メール確認を促すビューファイル
        return view('auth.verify-email');
    }
}
