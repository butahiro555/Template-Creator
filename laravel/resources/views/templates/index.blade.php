@extends('layouts.app')
@include('commons.navbar')

@section('content')
    <h5 class="text-center text-primary">テンプレート作成画面</h5>
    <div class="top">
        <form action="{{ route('templates.store') }}" method="POST">
            @csrf
            <div class="container">
                @if(Auth::check())
                    <div class="title">
                        <input type="text" name="title" class="form-control" placeholder="タイトル">
                    </div>
                    <div class="textarea">
                        <textarea id="copyTarget" name="content" type="text" rows="13" class="form-control" placeholder="保存、またはコピーしたい文章を入力してください。"></textarea>
                    </div>
                @else
                    <div class="textarea">
                        <textarea id="copyTarget" name="content" type="text" rows="13" class="form-control" placeholder="ユーザー登録をすることで、様々な機能を利用することが可能です。"></textarea>
                    </div>
                @endif
                
                <div class="wrapper">
                    @if(Auth::check())
                        <div class="wrapper-button">
                            <button type="submit" class="btn btn-primary">保存</button>
                        </div>
                    @endif
                    <div class="wrapper-button">
                        <button type="button" onclick="copyToClipboard('copyTarget')" class="btn btn-info">コピー</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/copyMessage.js') }}" defer></script>
@endsection
