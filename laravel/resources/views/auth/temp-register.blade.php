@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <h1>Temporary Registration</h1>
            </div>
            @if ($errors->has('verification'))
                <div class="alert alert-danger">
                    {{ $errors->first('verification') }}
                </div>
            @endif
            <form id="formClear" action="{{ route('temp-user.handle') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>               
                <div class="text-right">
                    <x-primary-button class="btn btn-success" name="action" value="send_verification">
                        {{ __('Send Verification Email') }}
                    </x-primary-button>
                    <x-primary-button class="btn btn-info" name="action" value="resend_verification">
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
