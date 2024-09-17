@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <h1>Signup</h1>
            </div>
            <form id="formClear" action="{{ route('forgot-password.reset') }}" method="POST">
                @csrf
                <div class="mb-3 mt-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email') ?? $email" required readonly />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>
                <div class="mb-3">
                    <x-input-label for="verification_code" :value="__('Verification Code')" />
                    <x-text-input id="verification_code" class="form-control" type="text" name="verification_code" required />
                    <x-input-error :messages="$errors->get('verification_code')" class="mt-2 text-danger" />
                </div>
                <div class="mb-3">
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>
                <div class="mb-3">
                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                </div>
		        <div class="d-flex justify-content-end align-items-center mt-4">
                    <x-primary-button class="btn btn-info" style="width: auto;">
                        {{ __('Password Reset') }}
                    </x-primary-button>
		            <x-primary-button type="button" class="btn btn-secondary w-25:" onclick="window.location.href='{{ route('templates.index') }}'">
                        {{ __('Cancel') }}
                    </x-primary-button>
                </div>
            </form>
    	</div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
