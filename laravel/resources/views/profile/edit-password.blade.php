@extends('layouts.app')

@section('content')
<body class="d-flex flex-column align-items-center" style="height: 100vh;">
    <div class="container d-flex flex-column justify-content-center" style="height: 90vh;">
        <div class="border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h5 class="text-center mb-4">パスワード更新画面</h5>
            <form id="formClear" action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="password">新しいパスワード</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="新しいパスワードを入力してください" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">新しいパスワードの確認</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="新しいパスワードと同じパスワードを入力してください" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-warning text-white">パスワードを更新する</button>
                </div>
            </form>
        </div>
    </div>
</body>
@endsection

@section('scripts')
    <script src="{{ asset('js/formClear.js') }}" defer></script>
@endsection
