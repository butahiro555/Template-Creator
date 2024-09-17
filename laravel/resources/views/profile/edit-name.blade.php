@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column justify-content-center" style="height: 90vh;">
    <div class="content-wrapper border p-3 mb-4 rounded w-100" style="max-width: 900px;">
            <h5 class="text-center mb-4">User Name Edit</h5>
	        <form action="{{ route('profile.update-name') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="name">User Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{ old('name', $user->name) }}">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-warning text-white">Update</button>
                </div>
        </form>
    </div>
</div>
@endsection
