@extends('layouts.app')

@section('content')
    <div style="text-align: center; padding: 50px; background-color: #f8f9fa; font-family: Arial, sans-serif;">
        <h1 style="color: #dc3545; font-size: 2.5em; margin-bottom: 20px;">401 - 認証が必要</h1>
        <p style="font-size: 1.2em; color: #333; margin-bottom: 20px;">
            このページにアクセスするにはログインが必要です。
        </p>
        <a href="{{ url('/login') }}" style="display: inline-block; padding: 10px 20px; font-size: 1.1em; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">
            ログインページに移動
        </a>
    </div>
@endsection

