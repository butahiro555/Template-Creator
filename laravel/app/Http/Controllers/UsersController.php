<?php

namespace App\Http\Controllers;

use App\Models\TempUser;
use App\Models\User;
use App\Mail\RegistrationCompleted;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // ログ出力用のファサードをインポート
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

            return redirect()->route('login')->withErrors(['token' => trans('validation.token_expired')]);
        }
    
        return view('auth.register', ['email' => $tempUser->email]);
    }

    public function register(Request $request)
    {
        $this->validationsController->validateRegisterForm($request);

        // メールアドレスをリクエストから取得
        $email = $request->input('email');
        
        // 仮ユーザーをメールアドレスで取得
        $tempUser = TempUser::where('email', $email)->first();
        
        // 仮ユーザーが存在しない場合の処理
        if (!$tempUser) {
            return redirect()->back()->withErrors(['email' => 'ユーザー情報が見つかりません。']);
        }

        // 認証コードを確認
        if (is_null($tempUser->verification_code) || $tempUser->verification_code !== $request->verification_code) {
            return redirect()->back()->withErrors(['verification' => trans('validation.verification_code_incorrect')]);
        }

        // 認証コードの有効期限を確認
        if (is_null($tempUser->verification_code_expires_at) || $tempUser->verification_code_expires_at <= now()) {
            return redirect()->back()->withErrors(['verification' => trans('validation.verification_code_expired')]);
        }
        
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
        Log::info('Redirecting to login with success status.');
            
        // セッションの内容をログに出力
        Log::info('Session Data After Success:', ['session' => $request->session()->all()]);
    
    return redirect()->route('login')->with('status', '登録が完了しました！');
    } 
}
