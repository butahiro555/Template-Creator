@extends('layouts.app')
@include('commons.navbar')

@section('content')
    @if(isset($templates) && count($templates) > 0)
        <h5 class="text-center text-danger">Template list</h5>
        
        <table class="search">
            <tr>
                <form action="{{ route('search') }}" method="GET">
                    <td>
                        <input type="text" name="keyword" class="form-control" placeholder="Search Templates title." required>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-success text-white">
                            <i class="fas fa-search"></i>
                        </button>
                    </td>
                </form>
            </tr>
        </table>
        
        <div class="sort">
            <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort</button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => 'asc']) }}">Create Date Ascending</a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => 'desc']) }}">Create Date Descending</a>
                </li>
                <li class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => 'asc']) }}">Update Date Ascending</a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => 'desc']) }}">Update Date Descending</a>
                </li>
            </ul>
        </div>

        <!-- テンプレートを繰り返し表示する -->
        @foreach ($templates as $template)
            <div class="container">
                <!-- 更新のフォームアクション -->
                <form action="{{ route('templates.update', $template->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="title">
                        <input type="text" name="title" value="{{ $template->title }}" class="form-control" placeholder="Title">
                    </div>
                    
                    <div class="textarea">
                        <textarea id="copyTarget{{ $template->id }}" name="content" rows="13" class="form-control" placeholder="Type your text.">{{ $template->content }}</textarea>
                    </div>
                        
                    <!-- 3つのボタンのためのflex box -->        
                    <div class="flex_test-box">
                        <div class="flex_test-item">
                            <!-- 更新ボタン -->
                            <button type="submit" class="btn btn-warning text-white update-btn">Update</button>
                        </div>
                </form>
                        
                <!-- 削除のフォーム -->
                <form action="{{ route('templates.destroy', $template->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex_test-item">
                        <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                    </div>
                </form>
                        
                <!-- クリップボードにコピーするボタン -->
                <div class="flex_test-item">
                    <button type="button" onclick="copyToClipboard('copyTarget{{ $template->id }}')" class="btn btn-info">Copy</button>
                </div>
            </div>
                    
            <!-- テンプレート間の仕切り -->
            <div class="dropdown-divider"></div>
        </div>
        @endforeach
                
        <!-- ページネーション -->
        <div class="d-flex justify-content-center">
            {{ $templates->links('pagination::bootstrap-4') }}
        </div>

    @else
        <h5 class="mt-5 text-center">Template is not found.</h5>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/copyMessage.js') }}" defer></script>
    <script src="{{ asset('js/deleteOrUpdate-messages.js') }}" defer></script>
@endsection
