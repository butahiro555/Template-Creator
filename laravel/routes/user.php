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

    // 本登録関連
	Route::get('register', [UsersController::class, 'showRegistrationForm'])->name('register.show');
    Route::post('register', [UsersController::class, 'register'])->name('register');

    // ログイン関連
    Route::get('login', [AuthsController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthsController::class, 'login']);
});

// 認証されたユーザーにアクセスを許可するためのルートグループ
Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthsController::class, 'logout'])->name('logout');// ログアウト関連
});
