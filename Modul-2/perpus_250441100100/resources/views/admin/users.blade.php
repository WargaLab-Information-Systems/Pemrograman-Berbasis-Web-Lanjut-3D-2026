<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">

                    <h3 class="text-lg font-semibold mb-4">
                        Tambah Pengguna Baru
                    </h3>

                    <form action="{{ route('admin.users.store') }}"
                          method="POST"
                          class="grid grid-cols-1 md:grid-cols-5 gap-4">

                        @csrf

                        <div>
                            <label class="block mb-1">
                                Nama
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full border-gray-300 rounded-md"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-1">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="w-full border-gray-300 rounded-md"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-1">
                                Password
                            </label>

                            <input type="password"
                                   name="password"
                                   class="w-full border-gray-300 rounded-md"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-1">
                                Role
                            </label>

                            <select name="role"
                                    class="w-full border-gray-300 rounded-md"
                                    required>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Tambah
                            </button>
                        </div>

                    </form>

                    @if ($errors->any())
                        <div class="mt-4 text-red-600">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-lg font-semibold mb-4">
                        Daftar Pengguna
                    </h3>

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left">
                                        Nama
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Email
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Role Saat Ini
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Ubah Role
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($users as $user)
                                    <tr class="border-b">

                                        <td class="px-4 py-3">
                                            {{ $user->name }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $user->email }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ ucfirst($user->getRoleNames()->first() ?? 'Tanpa Role') }}
                                        </td>

                                        <td class="px-4 py-3">

                                            <form action="{{ route('admin.users.role', $user) }}"
                                                  method="POST"
                                                  class="flex gap-2">

                                                @csrf
                                                @method('PUT')

                                                <select name="role"
                                                        class="border-gray-300 rounded-md">

                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}"
                                                            @selected($user->hasRole($role->name))>
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                <button type="submit"
                                                        class="px-3 py-1 bg-red-500 text-white rounded">
                                                    Simpan
                                                </button>

                                            </form>

                                        </td>

                                        <td class="px-4 py-3">

                                            @if ($user->id !== auth()->id())

                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="px-3 py-1 bg-red-600 text-white rounded">
                                                        Hapus
                                                    </button>

                                                </form>

                                            @else
                                                <span class="text-gray-500">
                                                    Akun Anda
                                                </span>
                                            @endif

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-4 text-center text-gray-500">
                                            Belum ada data pengguna.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>