@extends('layouts.app')

@section('content')
    <div class="container d-flex flex-column justify-content-center" style="height: 90vh;">
        <div class="content-wrapper border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h5 class="text-center mb-4">Confirm Your Current Password</h5>
            <form id="formClear" action="{{ route('profile.delete-user-password-confirm') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="password">Current Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your current password" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-warning text-white">Confirm</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
