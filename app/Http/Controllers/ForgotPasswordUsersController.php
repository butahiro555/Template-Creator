<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ForgotPasswordUser;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordResetCompleted;
use App\Services\TokenService;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
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

    // パスワードを忘れたユーザー向けのメール認証処理
    public function sendResetForUser(Request $request)
    {
        // メールアドレスのバリデーション
        $this->validationsController->validateEmail($request);
        $email = strtolower($request->email);

        // 登録済みユーザーを検索
        $registeredUser = User::where('email', $email)->first();

        // 登録済みでなければ、エラーメッセージを返す
        if (!$registeredUser) {
            return redirect()->back()->withErrors(['email' => trans('error_message.user_not_found')]);
        }

        // パスワードを忘れたユーザーを検索
        $forgotPasswordUser = ForgotPasswordUser::where('email', $email)->first();

        // パスワードを忘れたユーザーが存在しない場合、新規作成
        if (!$forgotPasswordUser) {
            $forgotPasswordUser = ForgotPasswordUser::createOrUpdateForgotPasswordUser($email);
            $verificationCode = $forgotPasswordUser->verification_code;
            $token = $forgotPasswordUser->token;
            $verificationUrl = route('forgot-password.resetform', ['token' => $token]);
            $forgotPasswordUser->save();

            // メール送信
            try {
                Mail::to($email)->queue(new PasswordResetMail($verificationUrl, $verificationCode));
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['email' => trans('error_message.unexpected_error')]);
            }

            // メール確認を促すビューファイル
            return redirect()->route('verify-your-email');
        }

        // パスワードを忘れたユーザーが存在する場合（再送信処理）
        if ($forgotPasswordUser->resend_count < 3) {
            // 再送信回数を更新
            $forgotPasswordUser->resend_count += 1;
            $forgotPasswordUser->save();

            // メール再送信
            $forgotPasswordUser = ForgotPasswordUser::createOrUpdateForgotPasswordUser($email);
            $verificationCode = $forgotPasswordUser->verification_code;
            $token = $forgotPasswordUser->token;
            $verificationUrl = route('forgot-password.resetform', ['token' => $token]);
            $forgotPasswordUser->save();

            try {
                Mail::to($email)->queue(new PasswordResetMail($verificationUrl, $verificationCode));
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['email' => trans('error_message.unexpected_error')]);
            }

            // メール確認を促すビューファイル
            return redirect()->route('verify-your-email');
        }

        // 再送信回数が上限に達している場合
        return redirect()->back()->withErrors(['email' => trans('error_message.resend_limit')]);
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
            Mail::to($user->email)->queue(new PasswordResetCompleted());

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
