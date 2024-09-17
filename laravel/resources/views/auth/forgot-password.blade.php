@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <h2 class="mb-3">Password Reset</h2>
	        </div>
            <form id="formClear" action="{{ route('forgot-password.send') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>
                <div class="d-flex justify-content-end align-items-center mt-4">
                    <x-primary-button class="btn btn-info" style="width: auto;">
                        {{ __('Password Reset') }}
                    </x-primary-button>
                    <x-primary-button type="button" class="btn btn-secondary ml-2" onclick="history.back();">
                        {{ __('Back') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
