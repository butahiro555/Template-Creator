<?php

use App\Http\Controllers\TempUsersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthsController;
use Illuminate\Support\Facades\Route;

// ゲストユーザーにアクセスを許可するためのルートグループ
Route::middleware('guest')->group(function () {

    // ユーザー仮登録
    Route::get('register/temp', [TempUsersController::class, 'create'])->name('temp-user.create'); // ユーザー仮登録画面を表示
    Route::post('register/temp', [TempUsersController::class, 'store'])->name('temp-user.store'); // ユーザー仮登録情報を送信

    // ユーザー本登録
	Route::get('register/{token}', [UsersController::class, 'showRegistrationForm'])->name('register.show'); // トークンURLを持った専用ユーザー登録画面を表示
    Route::post('register', [UsersController::class, 'register'])->name('register'); // ユーザー登録情報を送信

    // ログイン
    Route::get('login', [AuthsController::class, 'showLoginForm'])->name('login'); // ログイン画面を表示
    Route::post('login', [AuthsController::class, 'login']); // ログイン情報を送信
});

// 認証されたユーザーにアクセスを許可するためのルートグループ
Route::middleware('auth')->group(function () {

    // ログアウト
    Route::post('logout', [AuthsController::class, 'logout'])->name('logout');
});
