<?php

use App\Http\Controllers\TempUsersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthsController;
use App\Http\Controllers\ProfileController;
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

    //メール確認を促すリダイレクト先
    Route::get('verify-your-email', function () {
        return view('auth.verify-email');
    })->name('verify-your-email');

    // 退会後のリダイレクト先
    Route::get('goodbye', function () {
        return view('goodbye');
    })->name('goodbye');
});

// 認証されたユーザーにアクセスを許可するためのルートグループ
Route::middleware('auth')->group(function () {

    // プロフィール一覧
    Route::get('profile', [ProfileController::class, 'showProfile'])->name('profile.index');

    // ユーザー名変更機能
    Route::get('profile/edit-name/form', [ProfileController::class, 'showEditNameForm'])->name('profile.edit-name'); // ユーザー名変更画面を表示
    Route::patch('profile/edit-name/send', [ProfileController::class, 'updateNameSend'])->name('profile.update-name');

    // パスワード変更機能
    Route::get('profile/edit-password/confirm-password', [ProfileController::class, 'confirmCurrentPasswordForm'])->name('profile.password-confirm-form'); // 現パスワード入力フォームの表示
    Route::post('profile/edit-password/confirm-password', [ProfileController::class, 'confirmCurrentPassword'])->name('profile.password-confirm'); // 現パスワードの確認処理
    Route::match(['get', 'post'], 'profile/edit-password', [ProfileController::class, 'showEditPasswordForm'])->name('profile.edit-password'); // パスワード変更フォームの表示
    Route::patch('profile/edit-password/send', [ProfileController::class, 'updatePasswordSend'])->name('profile.update-password');

    // ユーザー退会機能
    Route::get('profile/delete-user/confirm-password', [ProfileController::class, 'confirmCurrentPasswordFormForDeleteUser'])->name('profile.delete-user-password-confirm-form'); // 現在のパスワード入力フォーム
    Route::post('profile/delete-user/confirm-password', [ProfileController::class, 'confirmCurrentPasswordForDeleteUser'])->name('profile.delete-user-password-confirm'); // 現パスワードの確認処理
    Route::match(['get', 'post'], 'profile/delete-user/form', [ProfileController::class, 'deleteUserForm'])->name('profile.delete-user-form'); // 退会確認画面
    Route::delete('profile/delete-user/form', [ProfileController::class, 'destroy'])->name('profile.delete-user-send');

    // ログアウト
    Route::post('logout', [AuthsController::class, 'logout'])->name('logout');
});
