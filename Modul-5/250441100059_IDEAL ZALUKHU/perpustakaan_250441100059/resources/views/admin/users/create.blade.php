
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-6">
                    Formulir Tambah Pengguna
                </h3>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="name" class="block font-medium mb-2">
                            Nama Pengguna
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
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
                            value="{{ old('email') }}"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >

                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block font-medium mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="8"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >

                        @error('password')
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
                            <option value="">-- Pilih Role --</option>

                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role->name }}"
                                    {{ old('role') == $role->name ? 'selected' : '' }}
                                >
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>

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
                            Simpan Pengguna
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
