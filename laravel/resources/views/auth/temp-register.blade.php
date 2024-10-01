@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 600px; width: 100%;">
            <div class="text-center">
                <h2>仮ユーザー登録画面</h2>
            </div>
            <form id="formClear" action="{{ route('temp-user.handle') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" required />
                </div>               
                <div class="text-right">
                    <x-primary-button class="btn btn-success" name="action" value="send_verification">
                        {{ __('認証コード発行') }}
                    </x-primary-button>
                    <x-primary-button class="btn btn-info" name="action" value="resend_verification">
                        {{ __('認証コード再発行') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
