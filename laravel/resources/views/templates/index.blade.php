@extends('layouts.app')
@include('commons.navbar')

@section('content')
    <h5 class="text-center text-primary">Template create</h5>
    <div class="top">
        <form action="{{ route('templates.store') }}" method="POST">
            @csrf
            <div class="container">
                @if(Auth::check())
                    <div class="title">
                        <input type="text" name="title" class="form-control" placeholder="Title">
                    </div>
                    <div class="textarea">
                        <textarea id="copyTarget" name="content" type="text" rows="13" class="form-control" placeholder="Type your text."></textarea>
                    </div>
                @else
                    <div class="textarea">
                        <textarea id="copyTarget" name="content" type="text" rows="13" class="form-control" placeholder="If you login on this site, you can use function of create and save templates!"></textarea>
                    </div>
                @endif
                
                <div class="wrapper">
                    @if(Auth::check())
                        <div class="wrapper-button">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    @endif
                    <div class="wrapper-button">
                        <button type="button" onclick="copyToClipboard('copyTarget')" class="btn btn-info">Copy</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/copyMessage.js') }}" defer></script>
@endsection
