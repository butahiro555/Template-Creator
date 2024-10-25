@extends('layouts.app')
@include('commons.navbar')

@section('content')
    <h5 class="text-center text-primary">テンプレート作成画面</h5>
    <div class="top">
        <form action="{{ route('templates.store') }}" method="POST">
            @csrf
            @if(Auth::check())
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="タイトル" required>
            </div>
            <div class="form-group">
                <textarea id="copyTarget" name="content" rows="13" class="form-control" placeholder="保存、またはコピーしたい文章を入力してください。"></textarea>
            </div>
            @else
            <div class="form-group">
                <textarea id="copyTarget" name="content" rows="13" class="form-control" placeholder="ユーザー登録をすることで、様々な機能を利用することが可能です。"></textarea>
            </div>
            @endif
        
            <div class="d-flex justify-content-end">
            @if(Auth::check())
                <button type="submit" class="btn btn-primary mr-2">保存</button>
            @endif
                <button type="button" onclick="copyToClipboard('copyTarget')" class="btn btn-info">コピー</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/copyMessage.js') }}" defer></script>
@endsection