@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 600px; width: 100%;">
            <div class="text-center">
                <h4 class="mb-3">パスワードをリセットするために、登録メールアドレス宛に認証コードを発行してください。</h4>
	        </div>
            <form id="formClear" action="{{ route('forgot-password.send') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-end align-items-end mt-4">
                    <x-primary-button class="btn btn-info" style="width: auto;">
                        {{ __('認証コード発行') }}
                    </x-primary-button>
                    <x-primary-button type="button" class="btn btn-secondary mt-2 mt-md-0 ml-md-2" style="width: auto;" onclick="window.location.href='{{ route('login') }}'">
                        {{ __('戻る') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
