@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-2 shadow-sm" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <h2>パスワードリセット画面</h2>
            </div>
            <form id="formClear" action="{{ route('forgot-password.reset') }}" method="POST">
                @csrf
                <div class="mb-3 mt-3 pt-2 pr-2 pl-2">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email') ?? $email" required readonly />
                </div>
                <div class="mb-3 pr-2 pl-2">
                    <x-input-label for="verification_code" :value="__('認証コード')" />
                    <x-text-input id="verification_code" class="form-control" type="text" name="verification_code" required />
                </div>
                <div class="mb-3 pr-2 pl-2">
                    <x-input-label for="password" :value="__('新しいパスワード')" />
                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                </div>
                <div class="mb-3 pr-2 pl-2">
                    <x-input-label for="password_confirmation" :value="__('新しいパスワードの確認')" />
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-end align-items-end mt-4">
                    <x-primary-button class="btn btn-info" style="width: auto;">
                        {{ __('パスワードリセット') }}
                    </x-primary-button>
                    <x-primary-button type="button" class="btn btn-secondary mt-2 mt-md-0 ml-md-2" style="width: auto;" onclick="window.location.href='{{ route('templates.index') }}'">
                        {{ __('キャンセル') }}
                    </x-primary-button>
                </div>
            </form>
    	</div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
