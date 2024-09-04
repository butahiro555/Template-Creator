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
	    
	    $token = $request->token;

	    // トークンに基づいて仮ユーザーを取得
        $tempUser = TempUser::where('token', $token)
                            ->where('token_expires_at', '>', now())
                            ->first();
    
        // 仮ユーザーが存在するか確認
        if (is_null($tempUser)) {
            Log::info('Verification code error:', [
                'input_code' => $request->verification_code,
                'saved_code' => 'TempUser not found'
            ]);
            return redirect()->back()->withErrors(['verification' => trans('validation.verification_invalid')]);
        }

        // 認証コードを確認
        if ($tempUser->verification_code !== $request->verification_code) {
            Log::info('Verification code error:', [
                'input_code' => $request->verification_code,
                'saved_code' => $tempUser->id
            ]);
            return redirect()->back()->withErrors(['verification' => trans('validation.verification_code_incorrect')]);
        }

        // 検証コードの有効期限を確認
        if ($tempUser->verification_code_expires_at <= Carbon::now()) {
            Log::info('Verification code error:', [
                'input_code' => $request->verification_code,
                'saved_code' => 'Verification code expired'
            ]);
            return redirect()->back()->withErrors(['verification' => trans('validation.verification_code_expired')]);
        }

        // トークンの有効期限を確認
        if ($tempUser->token_expires_at <= Carbon::now()) {
            Log::info('Verification code error:', [
                'input_code' => $request->token_expires_at,
                'saved_code' => 'Token expired'
            ]);
            return redirect()->back()->withErrors(['verification' => trans('validation.token_expired')]);
        }

            // すべての条件が満たされない場合のデフォルトエラー
            Log::info('Unexpected verification error:', [
                'input_code' => $request->verification_code,
                'saved_code' => 'Unexpected error'
            ]);
            return redirect()->back()->withErrors(['verification' => trans('validation.unexpected_error')]);
        
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
