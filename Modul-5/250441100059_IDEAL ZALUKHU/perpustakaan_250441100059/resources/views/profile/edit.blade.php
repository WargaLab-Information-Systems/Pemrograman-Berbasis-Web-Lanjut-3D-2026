@php
    $layout = match (true) {
        auth()->user()->hasRole('admin') => 'admin-layout',
        auth()->user()->hasRole('petugas') => 'petugas-layout',
        default => 'siswa-layout',
    };
@endphp

<x-dynamic-component :component="$layout">
    <div class="student-profile-page p-6">
        <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
        <div class="student-profile-grid mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="student-profile-card overflow-hidden rounded-lg border bg-white shadow-sm">
                <div class="p-6" x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            <div class="student-profile-card overflow-hidden rounded-lg border bg-white shadow-sm">
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
