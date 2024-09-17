@extends('layouts.app')

@section('content')
<body class="d-flex flex-column align-items-center" style="height: 100vh;">
    <div class="container d-flex flex-column justify-content-center" style="height: 90vh;">
        <div class="border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h5 class="text-center mb-4">Update Your Password</h5>
            <form id="formClear" action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-warning text-white">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</body>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
