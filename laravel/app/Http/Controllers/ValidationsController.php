<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidationsController extends Controller
{
    // メールバリデーション用メソッド
    public function validateEmail(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    }
}
