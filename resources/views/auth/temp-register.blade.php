@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 600px; width: 100%;">
            <div class="text-center mt-2">
                <h2>仮ユーザー登録画面</h2>
            </div>
            <form id="formClear" action="{{ route('temp-user.send') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" required />
                </div>               
                <div class="d-flex flex-column flex-md-row justify-content-end align-items-end mt-4">
                    <x-primary-button class="btn btn-success" style="width: auto;" value="send_verification">
                        {{ __('認証コード発行') }}
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
