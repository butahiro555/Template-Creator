<?php

namespace App\Http\Controllers;

use App\Models\TempUser;
use App\Models\User;
use App\Mail\RegistrationCompleted;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class UsersController extends Controller
{
    // 初期化するための変数を準備
    protected $validationsController;

    // コンストラクタで ValidationsController のインスタンスを受け取り、プロパティに代入
    public function __construct(ValidationsController $validationsController)
    {
        // 受け取った ValidationsController インスタンスをプロパティに設定
        $this->validationsController = $validationsController;
    }

    // 本登録ページを表示するメソッド
    public function showRegistrationForm(Request $request)
    {
        $token = $request->token;
    
        // トークンに基づいて仮ユーザーを取得
        $tempUser = TempUser::where('token', $token)
                            ->where('token_expires_at', '>', now())
                            ->first();
    
        // 仮ユーザーが見つからない場合やトークンが期限切れの場合
        if (!$tempUser) {

            return redirect()->route('login')->withErrors(['token' => trans('error_message.token_expired')]);
        }
    
        return view('auth.register', ['email' => $tempUser->email]);
    }

    // ユーザー本登録処理
    public function register(Request $request)
    {
        $this->validationsController->validateRegisterForm($request);
    
        $email = $request->email;
    
        try {
            // トランザクション開始
            DB::transaction(function () use ($email, $request) {
                // 仮ユーザーをメールアドレスで取得
                $tempUser = TempUser::where('email', $email)->first();
                
                // 仮ユーザーが存在しない場合の処理
                if (!$tempUser) {
                    throw new \Exception(trans('error_message.user_not_found'));
                }
    
                // 認証コードを確認
                if (is_null($tempUser->verification_code) || $tempUser->verification_code !== $request->verification_code) {
                    throw new \Exception(trans('error_message.verification_code_incorrect'));
                }
    
                // 認証コードの有効期限を確認
                if (is_null($tempUser->verification_code_expires_at) || $tempUser->verification_code_expires_at <= now()) {
                    throw new \Exception(trans('error_message.verification_code_expired'));
                }
    
                // 本登録ユーザーを作成
                $user = User::create([
                    'name' => $request->name,
                    'email' => $tempUser->email,
                    'password' => Hash::make($request->password),
                ]);
    
                // 仮ユーザーのデータを削除
                $tempUser->delete();
            });
    
            // トランザクション終了後、メール送信を実行
            Mail::to($email)->queue(new RegistrationCompleted());
    
            // ログイン画面にリダイレクト
            return redirect()->route('login')->with(['status' => trans('success_message.registered_successful')]);
    
        } catch (\Exception $e) {
            // エラーログを残してデバッグのために利用
            Log::error('User registration error:', ['error' => $e->getMessage()]);
        
            // セッションにエラーを追加してリダイレクト
            return redirect()->back()->withErrors(['tempUser' => $e->getMessage()]);
        }
    }
}
