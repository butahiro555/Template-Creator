<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthsController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // バリデーション
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $email = strtolower($request->email);
        $credentials = $request->only('email', 'password');
    
        // 登録済みユーザーを検索
        $registeredUser = User::where('email', $email)->first();
    
        // ユーザーが存在しない、またはゲストユーザーの場合
        if (!$registeredUser || $registeredUser->is_guest) {
            return redirect()->back()->withErrors(['email' => trans('error_message.email_invalid')]);
        }
    
        // 認証試行
        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors(['email' => trans('error_message.email_invalid')]);
        }
    
        // 認証成功時のリダイレクト
        return redirect()->intended('/');
    }

    public function logout()
    {
        // ゲストユーザーのテンプレート文を削除
        if (Auth::check() && Auth::user()->is_guest) {
            Template::where('user_id', Auth::id())->delete();
        }

        Auth::logout();
        return redirect('/');
    }
}
