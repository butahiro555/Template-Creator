<?php

use App\Http\Controllers\TempUsersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthsController;
use Illuminate\Support\Facades\Route;

// ゲストユーザーにアクセスを許可するためのルートグループ
Route::middleware('guest')->group(function () {
    // 仮ユーザー登録関連
    Route::get('register/temp', [TempUsersController::class, 'create'])->name('temp-user.create');
    Route::post('register/temp', [TempUsersController::class, 'store'])->name('temp-user.store');
    Route::get('/verify-email/{token}', [TempUsersController::class, 'verifyEmail'])->name('verify.email');

    // 本登録画面表示用のGETルート
	Route::get('register', [UsersController::class, 'showRegistrationForm'])->name('register.show');
	
	// 本登録関連
    Route::post('register', [UsersController::class, 'register'])->name('register');

    // ログイン関連
    Route::get('login', [AuthsController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthsController::class, 'login']);
});

// 認証されたユーザーにアクセスを許可するためのルートグループ
Route::middleware('auth')->group(function () {
    // ログアウト関連
    Route::post('logout', [AuthsController::class, 'logout'])->name('logout');
    
    // 認証後のリダイレクト先などを設定するルート
    Route::get('home', function () {
        return view('home'); // 認証後のリダイレクト先のビューを設定
    })->name('home');
});

