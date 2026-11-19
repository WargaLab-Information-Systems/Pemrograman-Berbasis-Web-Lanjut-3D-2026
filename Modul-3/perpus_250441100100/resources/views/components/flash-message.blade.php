@if (session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="p-4 bg-red-100 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
@endif
