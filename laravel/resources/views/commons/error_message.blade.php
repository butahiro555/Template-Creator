@if ($errors->any())
    <div class="alert alert-danger text-center">
        <p>{{ $errors->first() }}</p>
    </div>
@endif
