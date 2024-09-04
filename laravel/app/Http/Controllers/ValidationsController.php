<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidationsController extends Controller
{
    // メール用バリデーションチェック
    public function validateEmail(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    }

    // ユーザーネーム用バリデーションチェック
    public function validateName(Request $request)
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
    }

    // パスワード用バリデーションチェック
    public function validatePassword(Request $request)
    {
        return $request->validate([
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);
    }

    // メール、認証コード、パスワードのバリデーションチェック
    public function validateAuthInputs(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'email'],
            'verification_code' => ['required', 'string', 'size:5'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);
    }

    // ユーザー登録フォーム用バリデーションチェック
    public function validateRegisterForm(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'email'],
            'verification_code' => ['required', 'string', 'size:5'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);
    }

    // テンプレート用バリデーションチェック
    public function validateTemplate(Request $request)
    {
        return $request->validate([
            'title' => 'required|max:20',
            'content' => 'required|max:191',
        ]);
    }
}

