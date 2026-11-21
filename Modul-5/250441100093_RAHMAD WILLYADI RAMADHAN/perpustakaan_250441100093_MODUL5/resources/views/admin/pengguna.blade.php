<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pengguna & Role</h2>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-600">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="mb-6 bg-white p-6 shadow-sm sm:rounded-lg">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Pengguna Baru</h3>
            <form action="{{ route('admin.pengguna.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 font-bold">Simpan Pengguna Baru</button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Akun Terdaftar</h3>

            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border border-gray-200 p-3">Nama</th>
                        <th class="border border-gray-200 p-3">Email</th>
                        <th class="border border-gray-200 p-3">Role Saat Ini</th>
                        <th class="border border-gray-200 p-3 text-center">Aksi (Ubah Role / Hapus)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="border border-gray-200 p-3">{{ $user->name }}</td>
                        <td class="border border-gray-200 p-3">{{ $user->email }}</td>
                        <td class="border border-gray-200 p-3 font-bold uppercase text-sm">
                            {{ $user->getRoleNames()->first() ?? 'Tidak ada' }}
                        </td>
                        <td class="border border-gray-200 p-3">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('admin.pengguna.role', $user->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <select name="role" class="border-gray-300 rounded text-sm p-1 border">
                                        @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Simpan</button>
                                </form>

                                <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>