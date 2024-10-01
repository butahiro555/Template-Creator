@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 600px; width: 100%;">
            <div class="text-center">
                <h2>ユーザー登録画面</h2>
            </div>
            <form id="formClear" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3 mt-3">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email') ?? $email" required readonly />
                </div>
                <div class="mb-3">
                    <x-input-label for="verification_code" :value="__('認証コード')" />
                    <x-text-input id="verification_code" class="form-control" type="text" name="verification_code" required />
                </div>
                <div class="mb-3 mt-3">
                    <x-input-label for="name" :value="__('ユーザー名')" />
                    <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                <div class="mb-3">
                    <x-input-label for="password" :value="__('パスワード')" />
                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                </div>
                <div class="mb-3">
                    <x-input-label for="password_confirmation" :value="__('パスワード確認')" />
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('ユーザー登録がお済みの方はこちら') }}
                    </a>
                    <div class="d-flex w-50">
                        <x-primary-button class="btn-primary flex-grow-1">
                            {{ __('登録') }}
                        </x-primary-button>
                        <x-primary-button type="button" class="btn-secondary w-50" onclick="window.location.href='{{ route('templates.index') }}'">
                            {{ __('キャンセル') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
