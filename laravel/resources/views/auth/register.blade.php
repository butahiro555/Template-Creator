<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー本登録画面</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="rounded border p-4 shadow-sm" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <h1>Signup</h1>
            </div>

            <!-- Registration Form Start -->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-3 mt-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email') ?? $email" required readonly />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>

                <!-- Verification Code -->
                <div class="mb-3">
                    <x-input-label for="verification_code" :value="__('Verification Code')" />
                    <x-text-input id="verification_code" class="form-control" type="text" name="verification_code" required />
                    <x-input-error :messages="$errors->get('verification_code')" class="mt-2 text-danger" />
                </div>

                <!-- Name -->
                <div class="mb-3 mt-3">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                    <div class="d-flex w-50">
                        <!-- Register Button -->
                        <x-primary-button class="btm-primary flex-grow-1">
                            {{ __('Register') }}
                        </x-primary-button>
                        <!-- Cancel Button -->
                        <x-primary-button type="button" class="btn-secondary w-50" onclick="window.location.href='{{ route('templates.index') }}'">
                            {{ __('Cancel') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
            <!-- Registration Form End -->
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

