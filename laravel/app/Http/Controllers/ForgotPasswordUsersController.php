<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ForgotPasswordUser;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordResetCompleted;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;


class ForgotPasswordUsersController extends Controller
{
    protected $validationsController;

    public function __construct(ValidationsController $validationsController)
    {
        $this->validationsController = $validationsController;
    }

    public function forgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetForUser(Request $request)
    {
        // バリデーションの呼び出し
        $validatedData = $this->validationsController->validateEmail($request);

        $checkRegisteredUsers = User::where('email', $request->email)->first();
        
        // 登録済みユーザーかをチェック
        if (!$checkRegisteredUsers) {
            return redirect()->back()->withErrors(['email' => trans('error_message.user_not_found')]);
        }

        // メールアドレス宛に認証コードとトークンを発行
        $forgotPasswordUser = ForgotPasswordUser::createOrUpdateForgotPasswordUser($request->email);
        $verificationCode = $forgotPasswordUser->verification_code;
        $token = $forgotPasswordUser->token;
        $verificationUrl = route('forgot-password.resetform', ['token' => $token]);

        // セッションに認証コードと、トークンを保存
        session(['verification_code' => $verificationCode, 'email' => $request->email, 'token' => $token]);

        // メール送信
        Mail::to($request->email)->send(new PasswordResetMail($verificationUrl, $verificationCode));

        // メール確認を促すビューファイル
        return view('auth.verify-email');
    }

    // パスワードリセットフォーム
    public function showResetForm(Request $request)
    {   
        // リクエストからトークンを取得
        $token = $request->token;

        // トークンに基づいてパスワードリセットユーザーを取得
        $forgotPasswordUser = ForgotPasswordUser::where('token', $token)
                                ->where('token_expires_at', '>', now())
                                ->first();

        if (!$forgotPasswordUser) {
            return redirect()->route('auth.forgot-password')->withErrors(['expired' => trans('error_message.token_expired')]);
        }

        // トークンが有効な場合の処理をここに追加
        return view('auth.reset-password', ['email' => $forgotPasswordUser->email]);
    }

    // パスワードリセット
    public function passwordReset(Request $request)
    {
        $this->validationsController->validateAuthInputs($request);
    
        try {
            // トークンと認証コードを検証
            $forgotPasswordUser = ForgotPasswordUser::where('email', $request->email)
                ->where('verification_code', $request->verification_code)
                ->where('verification_code_expires_at', '>', Carbon::now())
                ->firstOrFail();

        } catch (ModelNotFoundException $e) {

            // 認証コードが一致しない、またはトークンが無効な場合
            return redirect()->back()->withErrors(['verification' => trans('error_message.verification_invalid')]);
        }
    
        // 本登録ユーザーのパスワードを更新　
        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = bcrypt($request->password);
        $user->save();
    
        // 仮ユーザーのデータを削除
        $forgotPasswordUser->delete();
    
        // 本登録完了メールを送信
        Mail::to($user->email)->send(new PasswordResetCompleted());
    
        // ログイン画面にリダイレクト
        return redirect()->route('login')->with(['status' => trans('success_message.password_reset_successful')]);
    }
}
