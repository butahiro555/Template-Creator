@extends('layouts.app')
@include('commons.navbar')

@section('content')
    @if(isset($templates) && count($templates) > 0)
        <h5 class="text-center text-danger">テンプレート一覧</h5>
        
        <div class="d-flex flex-column align-items-end mb-3" style="width: 100%; max-width: 300px;">
            <!-- 検索フォーム -->
            <form action="{{ route('search') }}" method="GET" class="form-inline mb-2 w-100">
                <input type="text" name="keyword" class="form-control mr-2 w-100" placeholder="タイトルを検索" required>
                <button type="submit" class="btn btn-success text-white">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <!-- 並び替えボタン -->
            <div class="sort w-100">
                <button type="button" class="btn btn-secondary dropdown-toggle w-100" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">並び替え</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => 'asc']) }}">作成日時 昇順</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => 'desc']) }}">作成日時 降順</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => 'asc']) }}">更新日時 昇順</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => 'desc']) }}">更新日時 降順</a></li>
                </ul>
            </div>
        </div>

        <!-- テンプレートを繰り返し表示する -->
        @foreach ($templates as $template)
            <div class="container mb-4">
                <!-- 更新と削除のフォーム -->
                <form action="{{ route('templates.update', $template->id) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <input type="text" name="title" value="{{ $template->title }}" class="form-control" placeholder="Title">
                    </div>
                    
                    <div class="form-group">
                        <textarea id="copyTarget{{ $template->id }}" name="content" rows="13" class="form-control" placeholder="Type your text.">{{ $template->content }}</textarea>
                    </div>
                        
                    <!-- ボタンを右揃えにするためのflex box -->        
                    <div class="d-flex justify-content-end">
                        <!-- 削除ボタン -->
                        <button type="button" class="btn btn-danger delete-btn mr-2" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $template->id }}').submit();">削除</button>

                        <!-- 更新ボタン -->
                        <button type="submit" class="btn btn-warning text-white update-btn mr-2">更新</button>
                        
                        <!-- クリップボードにコピーするボタン -->
                        <button type="button" onclick="copyToClipboard('copyTarget{{ $template->id }}')" class="btn btn-info">コピー</button>
                    </div>
                </form>

                <!-- 削除用のフォーム -->
                <form id="delete-form-{{ $template->id }}" action="{{ route('templates.destroy', $template->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- テンプレート間の仕切り -->
                <div class="dropdown-divider"></div>
            </div>
        @endforeach
                
        <!-- ページネーション -->
        <div class="d-flex justify-content-center">
            {{ $templates->links('pagination::bootstrap-4') }}
        </div>

    @else
        <h5 class="mt-5 text-center">テンプレートが見つかりません。</h5>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/copyMessage.js') }}" defer></script>
    <script src="{{ asset('js/deleteOrUpdate-messages.js') }}" defer></script>
@endsection