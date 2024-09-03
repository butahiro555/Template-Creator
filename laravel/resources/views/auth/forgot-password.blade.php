<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="rounded border p-4 shadow-sm" style="max-width: 500px; width: 100%;">
        <div class="text-center">
            <h2 class="mb-3">Password Reset</h2>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('forgot-password.send') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>

            <!-- Button Group -->
            <div class="d-flex justify-content-end align-items-center mt-4">
                <!-- Password Reset Button -->
                <x-primary-button class="btn btn-info" style="width: auto;">
                    {{ __('Password Reset') }}
                </x-primary-button>

                <!-- Back Button -->
                <x-primary-button type="button" class="btn btn-secondary ms-2" onclick="history.back();">
                    {{ __('Back') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
