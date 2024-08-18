@extends('layouts.app')

@section('content')
    @if(count($templates) > 0)
        <h5 class="text-center text-danger">Template list</h5>
        
        <table class="search">
            <form action="{{ route('search') }}" method="get">
                <td>
                    <input type="text" name="keyword" class="form-control" placeholder="Search Templates title.">
                </td>
                <td>
                    <button type="submit" class="btn btn-success text-white">
                        <i class="fas fa-search"></i>
                    </button>
                </td>
            </form>
        </table>
        
        <div class="sort">
            <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort</button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'created_at']) }}">Create Date</a>
                </li>
                <li class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'updated_at']) }}">Update Date</a>
                </li>
            </ul>
        </div>
        
        <!-- テンプレートを繰り返し表示する -->
        @foreach ($templates as $template)
                            
            <!-- 更新のフォームアクション -->
            <form action="{{ route('templates.update', $template->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="container">
                    <div class="title">
                        <input type="text" name="title" value="{{ $template->title }}" class="form-control" placeholder="Title">
                    </div>
                    
                    <div class="textarea">
                        <textarea id="copyTarget" name="content" rows="13" class="form-control" placeholder="Type your text.">{{ $template->content }}</textarea>
                    </div>
                        
                    <!-- 3つのボタンのためのflex box -->        
                    <div class="flex_test-box">
                        <div class="flex_test-item">
                            <!-- 更新ボタン -->
                            <button type="submit" id="warning" class="btn btn-warning text-white">Update</button>
                        </div>
            </form>
                        
                    <!-- 削除のフォーム -->
                    <form action="{{ route('templates.destroy', $template->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <div class="flex_test-item">
                            <button type="submit" id="delete" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                        
                        <!-- クリップボードにコピーするボタン -->
                        <div class="flex_test-item">
                            <button type="button" onclick="copyToClipboard()" class="btn btn-info">Copy</button>
                        </div>
                    </div>
                    
                    <!-- テンプレート間の仕切り -->
                    <div class="dropdown-divider"></div>
                </div>
        @endforeach
                
        <!-- ページネーション -->
        {{ $templates->links('pagination::bootstrap-4') }}
    @else
        <h5 class="mt-5 text-center">Template is not found.</h5>
    @endif
@endsection

