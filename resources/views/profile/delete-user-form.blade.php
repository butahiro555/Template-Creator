@extends('layouts.app')

@section('content')
    <div class="container d-flex flex-column justify-content-center" style="height: 90vh;">
        <div class="content-wrapper border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h3 class="mb-4">本当にアカウントを削除しますか？</h3>
            <p class="text-danger">アカウントを削除すると、元には戻せません。すべてのデータは永久に削除されます。</p>
            <div class="buttons-wrapper d-flex justify-content-end">
                <a href="{{ route('profile.index') }}" class="btn btn-secondary mr-3">
                    <i class="fas fa-arrow-left"></i> キャンセル
                </a>
                <form action="{{ route('profile.delete-user-send') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-user-slash"></i> アカウント削除
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
