<?php

namespace App\Http\Controllers;

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
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/'); // ログイン後のリダイレクト先を設定
        }

        return redirect()->back()->withErrors(['email' => trans('error_message.email_invalid')]);
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
