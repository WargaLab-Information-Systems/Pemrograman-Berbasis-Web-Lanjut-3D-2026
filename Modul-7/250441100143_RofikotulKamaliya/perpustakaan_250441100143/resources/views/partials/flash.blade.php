@if (session('success'))
    <p class="alert-success">{{ session('success') }}</p>
@endif

@if (session('error'))
    <p class="alert-error">{{ session('error') }}</p>
@endif

@if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
