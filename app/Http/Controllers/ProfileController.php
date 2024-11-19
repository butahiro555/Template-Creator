<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\ValidationsController;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    // 初期化するための変数を準備
    protected $validationsController;

    // コンストラクタで ValidationsController のインスタンスを受け取り、プロパティに代入
    public function __construct(ValidationsController $validationsController)
    {
        // 受け取った ValidationsController インスタンスをプロパティに設定
        $this->validationsController = $validationsController;
    }

    public function showProfile(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function showEditNameForm(Request $request): View
    {
        // ユーザーオブジェクト全体をビューに渡す
        return view('profile.edit-name', [
            'user' => $request->user(), // ユーザーオブジェクト全体を渡す
        ]);
    }

    public function updateNameSend(Request $request): RedirectResponse
    {   
        $validatedData = $this->validationsController->validateName($request);
    
        $user = $request->user();
    
        // 名前が変更されているかのチェック
        if ($user->name !== $validatedData['name']) {
            $user->name = $validatedData['name'];
            $user->save();
    
            // プロフィール画面にリダイレクトし、成功メッセージを表示
            return redirect()->route('profile.index')->with(['status' => trans('success_message.name_change_successful')]);

        } else {

            // 名前が変更されていない場合、エラーメッセージを返す
            return redirect()->back()->withErrors(['name' => trans('error_message.name_not_change')]);
        }
    }

    // 現パスワード入力フォームの表示
    public function confirmCurrentPasswordForm(Request $request): View
    {
        return view('profile.password-confirm-form', [
            'user' => $request->user(),
        ]);
    }

    // 現パスワードの確認処理
    public function confirmCurrentPassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (Hash::check($request->password, $user->password)) {
            return redirect()->route('profile.edit-password');
        } else {
            return redirect()->back()->withErrors(['password' => trans('error_message.password_is_wrong')]);
        }
    }

    // パスワード変更フォームの表示
    public function showEditPasswordForm(Request $request): View
    {
        if ($request->session()->has('password_changed')) {
            $request->session()->forget('password_changed'); // フラグをリセットする
        }

        return view('profile.edit-password');
    }

    // パスワード変更処理
    public function updatePasswordSend(Request $request): RedirectResponse
    {
        // パスワードバリデーションを実行
        $validatedData = $this->validationsController->validatePassword($request);
        $user = $request->user();

        // 現在のパスワードと新しいパスワードが同じかを確認
        if (Hash::check($validatedData['password'], $user->password)) {
            return redirect()->back()->withErrors(['password' => trans('error_message.password_not_change')]);
        }
        
        // 新しいパスワードをハッシュ化して保存
        $user->password = Hash::make($validatedData['password']);
        $user->save();
    
        // セッションの再生成
        $request->session()->regenerate();
    
        return redirect()->route('profile.index')->with(['status' => trans('success_message.password_change_successful')]);
    }

    // 退会希望者の現パスワード入力フォームの表示
    public function confirmCurrentPasswordFormForDeleteUser(Request $request): View
    {
        return view('profile.delete-user-password-confirm-form', [
            'user' => $request->user(),
        ]);
    }

    // 退会希望者の現パスワードの確認処理
    public function confirmCurrentPasswordForDeleteUser(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (Hash::check($request->password, $user->password)) {
            return redirect()->route('profile.delete-user-form');
        } else {
            return redirect()->back()->withErrors(['password' => trans('error_message.password_is_wrong')]);
        }
    }

    // 退会確認画面
    public function deleteUserForm(Request $request): View
    {
        return view('profile.delete-user-form');
    }

    // 退会処理
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        Auth::logout();  // ログアウト処理
        $user->delete(); // ユーザーの削除
    
        // セッションの無効化
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('goodbye'); // 退会完了後、専用ページへリダイレクト
    }
}
