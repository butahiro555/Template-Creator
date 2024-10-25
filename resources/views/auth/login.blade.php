@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 600px; width: 100%;">
            <div class="text-center">
                <h2>ログイン画面</h2>
            </div>
            <form id="formClear" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-input-label for="email" :value="__('メール')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>
                <div class="mb-3">
                    <x-input-label for="password" :value="__('パスワード')" />
                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                </div>
                <a href="{{ route('forgot-password.index') }}">
                        {{ __('パスワードを忘れた方はこちら') }}
                </a>
                <div class="d-flex justify-content-end align-items-center mt-4">
                    <div class="d-flex pb-2">
                        <x-primary-button class="btn btn-success" style="width: 120px;">
                            {{ __('ログイン') }}
                        </x-primary-button>
                        <x-primary-button type="button" class="btn btn-secondary ml-2" style="width: 140px;" onclick="window.location.href='{{ route('templates.index') }}'">
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