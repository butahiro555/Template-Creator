@extends('layouts.app')
@include('commons.navbar')

@section('content')
    <div class="container d-flex flex-column justify-content-center" style="height: 90vh;">
        <div class="border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h3 class="mb-4">User Profile</h3>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-grow-1">
                    <strong>E-mail:</strong>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-grow-1">
                    <strong>User Name:</strong>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-0">{{ $user->name }}</p>
                </div>
                <div>
                    <a href="{{ route('profile.edit-name') }}" class="btn btn-success text-white">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-grow-1">
                    <strong>Password:</strong>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-0">********</p>
                </div>
                <div>
                    <a href="{{ route('profile.password-confirm') }}" class="btn btn-success text-white">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h3 class="mb-4">Option</h3>
            <div class="d-flex justify-content-end">
                <form action="{{ route('profile.delete-user-password-confirm-form') }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-danger text-white">
                        <i class="fas fa-user-slash"></i> Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
