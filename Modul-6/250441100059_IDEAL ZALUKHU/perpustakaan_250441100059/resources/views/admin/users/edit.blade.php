
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-6">
                    Formulir Edit Pengguna
                </h3>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="name" class="block font-medium mb-2">
                            Nama Pengguna
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >

                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block font-medium mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >

                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="mb-6">
                        <label for="role" class="block font-medium mb-2">
                            Pilih Role
                        </label>

                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >
                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role->name }}"
                                    {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}
                                    {{ $user->is(auth()->user()) && $role->name !== 'admin' ? 'disabled' : '' }}
                                >
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>

                        @if ($user->is(auth()->user()))
                            <p class="mt-1 text-sm text-amber-600">
                                Demi keamanan sistem, role akun admin yang sedang digunakan tidak dapat diubah.
                            </p>
                        @endif

                        @error('role')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            style="background-color: #16a34a; color: white; padding: 10px 18px; border-radius: 6px; border: none; cursor: pointer;"
                        >
                            Simpan Perubahan
                        </button>

                        <a
                            href="{{ route('admin.users.index') }}"
                            style="background-color: #6b7280; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none;"
                        >
                            Kembali
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
