@extends('layouts.app')

@section('content')
    <div style="text-align: center; padding: 50px; background-color: #f8f9fa; font-family: Arial, sans-serif;">
        <h1 style="color: #dc3545; font-size: 2.5em; margin-bottom: 20px;">405 - メソッドが許可されていません</h1>
        <p style="font-size: 1.2em; color: #333; margin-bottom: 20px;">
            このリクエストメソッドは許可されていません。
        </p>
        <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 20px; font-size: 1.1em; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">
            ホームに戻る
        </a>
    </div>
@endsection

