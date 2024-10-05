<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ForgotPasswordUser;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordResetCompleted;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
    
        $email = $request->email;
    
        // 再送信回数をセッションから取得
        $resendCount = session()->get("resend_count_{$email}", 0);
    
        // 3回以上の場合はエラーメッセージを返す
        if ($resendCount >= 3) {
            return redirect()->back()->withErrors(['email' => trans('error_message.resend_limit')]);
        }
        
        // 登録済みユーザーであるかを検索
        $checkRegisteredUsers = User::where('email', $email)->first();
        
        // 登録済みでなければ、エラーメッセージを返す
        if (!$checkRegisteredUsers) {
            return redirect()->back()->withErrors(['email' => trans('error_message.user_not_found')]);
        }
    
        // メールアドレス宛に認証コードとトークンを発行
        $forgotPasswordUser = ForgotPasswordUser::createOrUpdateForgotPasswordUser($email);
        $verificationCode = $forgotPasswordUser->verification_code;
        $token = $forgotPasswordUser->token;
        $verificationUrl = route('forgot-password.resetform', ['token' => $token]);
    
        // メール送信
        Mail::to($email)->send(new PasswordResetMail($verificationUrl, $verificationCode));
    
        // セッションに再送信回数を保存
        session()->put("resend_count_{$email}", $resendCount + 1);
    
        // メール確認を促すビューファイル
        return redirect()->route('verify-your-email');
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
            return redirect()->route('login')->withErrors(['expired' => trans('error_message.token_expired')]);
        }

        // トークンが有効な場合の処理をここに追加
        return view('auth.reset-password', ['email' => $forgotPasswordUser->email]);
    }
    
    // パスワードリセット
    public function passwordReset(Request $request)
    {
        $this->validationsController->validateAuthInputs($request);
        
        $email = strtolower($request->email);
        $user = null;

        try {
            // トランザクションの開始
            DB::transaction(function () use (&$user, $email, $request) {
                // パスワードを忘れたユーザーをチェック
                $forgotPasswordUser = ForgotPasswordUser::where('email', $email)
                    ->where('verification_code', $request->verification_code)
                    ->where('verification_code_expires_at', '>', Carbon::now())
                    ->firstOrFail();

                // ユーザー情報を更新
                $user = User::where('email', $email)->firstOrFail();
                $user->password = bcrypt($request->password);
                $user->save();

                // ForgotPasswordUserデータの削除
                $forgotPasswordUser->delete();
            });

            // パスワード変更完了メールを送信
            Mail::to($user->email)->send(new PasswordResetCompleted());

            // セッション情報にカウントされていた送信回数をリセット
            session()->forget("resend_count_{$email}");

            // 処理成功時のリダイレクト
            return redirect()->route('login')->with(['status' => trans('success_message.password_reset_successful')]);

        } catch (\Exception $e) {
            // エラーログを残してデバッグのために利用
            Log::error('Password update error:', ['error' => $e->getMessage()]);

            // ユーザーにカスタムメッセージを返す
            return redirect()->back()->withErrors(['error' => trans('error_message.password_reset_fail')]);
        }
    }
}
