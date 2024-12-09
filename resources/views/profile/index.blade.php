@extends('layouts.app')
@include('commons.navbar')

@section('content')
    <div class="d-flex flex-column justify-content-center" style="min-height: 90vh;">
        <div class="rounded border p-4 shadow-sm" style="width: 100%;">
            <h3 class="mb-4 text-center">ユーザープロフィール</h3>

            <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                <div class="flex-grow-1 text-md-end">
                    <strong>メール:</strong>
                </div>
                <div class="flex-grow-1 text-md-start mt-2 mt-md-0">
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                <div class="flex-grow-1 text-md-end">
                    <strong>ユーザー名:</strong>
                </div>
                <div class="flex-grow-1 text-md-start mt-2 mt-md-0">
                    <p class="mb-0">{{ $user->name }}</p>
                </div>
                <div class="d-flex justify-content-end mt-2 mt-md-0">
                    <a href="{{ route('profile.edit-name') }}" class="btn btn-success text-white">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                <div class="flex-grow-1 text-md-end">
                    <strong>パスワード:</strong>
                </div>
                <div class="flex-grow-1 text-md-start mt-2 mt-md-0">
                    <p class="mb-0">********</p>
                </div>
                <div class="d-flex justify-content-end mt-2 mt-md-0">
                    <a href="{{ route('profile.password-confirm') }}" class="btn btn-success text-white">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded border p-4 mt-3 shadow-sm" style="width: 100%;">
            <h3 class="mb-4 text-center">オプション</h3>
            <div class="d-flex justify-content-center justify-content-md-end">
                <form action="{{ route('profile.delete-user-password-confirm-form') }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-danger text-white">
                        <i class="fas fa-user-slash"></i> アカウント削除
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection