
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Manajemen Pengguna
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <h3 class="font-semibold text-lg mb-4">
                    Daftar Pengguna
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 border">Nama</th>
                                <th class="p-3 border">Email</th>
                                <th class="p-3 border">Role</th>
                                <th class="p-3 border">Ubah Role</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="p-3 border">{{ $user->name }}</td>
                                    <td class="p-3 border">{{ $user->email }}</td>
                                    <td class="p-3 border">
                                        {{ $user->getRoleNames()->implode(', ') }}
                                    </td>
                                    <td class="p-3 border">
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <select name="role" class="border rounded p-2">
                                                    <option value="siswa" @selected($user->hasRole('siswa'))>
                                                        Siswa
                                                    </option>
                                                    <option value="petugas" @selected($user->hasRole('petugas'))>
                                                        Petugas
                                                    </option>
                                                    <option value="admin" @selected($user->hasRole('admin'))>
                                                        Admin
                                                    </option>
                                                </select>

                                                <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded">
                                                    Simpan
                                                </button>
                                            </form>
                                        @else
                                            Akun sendiri
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
