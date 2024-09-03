<?php

use App\Http\Controllers\TempUsersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthsController;
use App\Http\Controllers\ForgotPasswordUsersController;
use Illuminate\Support\Facades\Route;

// ゲストユーザーにアクセスを許可するためのルートグループ
Route::middleware('guest')->group(function () {

    // ユーザー仮登録
    Route::get('register/index', [TempUsersController::class, 'tempRegister'])->name('temp-user.index'); // ユーザー仮登録画面を表示
    Route::post('register/action', [TempUsersController::class, 'handleRequest'])->name('temp-user.handle'); //認証メール送信、再発行ボタンの処理を分ける
    Route::post('register/temp/send', [TempUsersController::class, 'mailSend'])->name('temp-user.send'); // ユーザー仮登録情報を送信
    Route::post('register/temp/resend', [TempUsersController::class, 'mailResend'])->name('temp-user.resend'); // 認証メール再発行

    // ユーザー本登録
	Route::get('register/{token}', [UsersController::class, 'showRegistrationForm'])->name('register.show'); // トークンURLを持った専用ユーザー登録画面を表示
    Route::post('register', [UsersController::class, 'register'])->name('register'); // ユーザー登録情報を送信

    // ログイン
    Route::get('login', [AuthsController::class, 'showLoginForm'])->name('login'); // ログイン画面を表示
    Route::post('login', [AuthsController::class, 'login']); // ログイン情報を送信

    // パスワードリセット
    Route::get('forgot-password', [ForgotPasswordUsersController::class, 'forgotPasswordForm'])->name('forgot-password.index'); // パスワード再設定画面を表示
    Route::post('forgot-password/send', [ForgotPasswordUsersController::class, 'sendResetForUser'])->name('forgot-password.send'); // 登録メールアドレス宛にパスワード再設定リンクURLを送信
    Route::get('forgot-password/resetform', [ForgotPasswordUsersController::class, 'showResetForm'])->name('forgot-password.resetform'); // トークンURLを持った専用ユーザーパスワードリセット画面を表示
    Route::post('forgot-password/reset', [ForgotPasswordUsersController::class, 'passwordReset'])->name('forgot-password.reset'); // 再設定パスワードを送信
});

// 認証されたユーザーにアクセスを許可するためのルートグループ
Route::middleware('auth')->group(function () {

    // ログアウト
    Route::post('logout', [AuthsController::class, 'logout'])->name('logout');
});
