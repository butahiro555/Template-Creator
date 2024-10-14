@extends('layouts.app')

@section('content')
    <div class="container d-flex flex-column justify-content-center" style="height: 90vh;">
        <div class="content-wrapper border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h5 class="text-center mb-4">パスワードを変更する前の確認画面</h5>
            <form id="formClear" action="{{ route('profile.password-confirm') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="password">現在のパスワード</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="現在のパスワードを入力してください" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-warning text-white">確認</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
