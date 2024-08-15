@extends('layouts.app')

@section('content')
            <div class="text-center">
                <textarea id="copyTarget" type="text" cols="60" rows="20" placeholder="Type your text."></textarea>
            </div>
            <div class="text-right">
                <button onclick="copyToClipboard()" type="button" class="btn btn-info">Copy</button>
            </div>
@endsection  
