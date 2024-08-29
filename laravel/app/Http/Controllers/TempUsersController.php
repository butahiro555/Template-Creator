<?php

namespace App\Http\Controllers;

use App\Models\TempUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\VerificationEmail;

class TempUsersController extends Controller
{   
    public function tempRegister()
    {
        return view('auth.temp-register');
    }

    // 認証メール送信と、再発行の仕分け
    public function handleRequest(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'send_verification') {
            return $this->send($request);
        } elseif ($action === 'resend_verification') {
            return $this->resend($request);
        }

        return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
    }

    // 認証コード発行
    public function send(Request $request)
    {
        if ($request->input('action') === 'send_verification') {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);

            // 入力されたメールアドレスが、登録済みのメールアドレスなのかを確認する
            $checkRegisteredTempUsers = TempUser::where('email', $request->email)->first();
            $checkRegisteredUsers = User::where('email', $request->email)->first();

            // 仮登録済みのメッセージ
            if($checkRegisteredTempUsers)
            {
                return redirect()->back()->withErrors(['email' => 'Please confirm you email or resend verify code.']);
            }

            // 本登録済みのメッセージ
            if($checkRegisteredUsers)
            {
                return redirect()->back()->withErrors(['email' => 'This email address is already registered.']);
            }

            // メールアドレス宛に認証コードとトークンを発行して送信
            $tempUser = TempUser::createOrUpdateTempUser($request->email);
            $verificationCode = $tempUser->verification_code;
            $token = $tempUser->token;
            $verificationUrl = route('register.show', ['token' => $token]);

            session(['verification_code' => $verificationCode, 'email' => $request->email, 'token' => $token]);

            Mail::to($request->email)->send(new VerificationEmail($verificationUrl, $verificationCode));

            return view('auth.verify-email');
        }

        return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
    }

    // 認証コード再発行
    public function resend(Request $request)
    {
        if ($request->input('action') === 'resend_verification') {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);

            // 仮ユーザーを検索
            $tempUser = TempUser::where('email', $request->email)->first();

            // 仮ユーザー登録のアドレスがなければ、メッセージを返す
            if (!$tempUser) {
                return redirect()->back()->withErrors(['email' => 'Email address not found.']);
            }
            
            // 仮ユーザー宛てに認証コードとトークンを再発行して送信
            $tempUser = TempUser::createOrUpdateTempUser($request->email);
            $verificationCode = $tempUser->verification_code;
            $token = $tempUser->token;
            $verificationUrl = route('register.show', ['token' => $token]);

            session(['verification_code' => $verificationCode, 'email' => $request->email, 'token' => $token]);

            Mail::to($request->email)->send(new VerificationEmail($verificationUrl, $verificationCode));

            return view('auth.verify-email');
        }

        return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
    }
}

