<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GuestLoginsController extends Controller
{
    public function guestLogin()
    {
        // 事前に登録したゲストユーザーを取得
        $guestUser = User::where('email', 'guestuser@example.com')->first();

        // ゲストユーザーでログイン
        Auth::login($guestUser);

        // ゲストユーザーのテンプレート文を削除
        if (Auth::check() && Auth::user()->is_guest) {
            Template::where('user_id', Auth::id())->delete();
        }

        // ログイン後、トップページなどにリダイレクト
        return redirect()->intended('/')->with(['status' => trans('success_message.login_as_guest')]);
    }
}
