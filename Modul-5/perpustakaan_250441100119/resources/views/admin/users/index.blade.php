@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
     style="padding-top: 36px; padding-bottom: 32px;">

    <h1 class="text-2xl font-bold text-gray-800"
        style="margin-bottom: 12px;">
        Kelola Pengguna
    </h1>

    <p class="text-gray-600"
       style="margin-bottom: 32px;">
        Halaman khusus Admin untuk melihat pengguna dan mengatur role.
    </p>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full min-w-[850px] divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">No.</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-4">
                            {{ $users->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-4">
                            {{ $user->name }}

                            @if ($user->id === auth()->id())
                                <span class="text-xs text-blue-600">
                                    (Akun Saya)
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            {{ $user->email }}
                        </td>

                        <td class="px-4 py-4">
                            {{ $user->getRoleNames()->implode(', ') ?: 'Belum ada role' }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            @can('kelola role pengguna')
                                @if ($user->id !== auth()->id())
                                    <form
                                        action="{{ route('admin.users.update-role', $user) }}"
                                        method="POST"
                                        class="flex items-center justify-center gap-2"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <select
                                            name="role"
                                            class="rounded-md border-gray-300 text-sm"
                                            required
                                        >
                                            @foreach ($roles as $role)
                                                <option
                                                    value="{{ $role->name }}"
                                                    @selected($user->hasRole($role->name))
                                                >
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button
                                            type="submit"
                                            style="background-color:#2563eb;color:white;padding:9px 16px;border-radius:8px;font-weight:600;cursor:pointer;white-space:nowrap;"
                                        >
                                            Simpan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-sm">
                                        Tidak bisa mengubah role sendiri
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-500">-</span>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="px-4 py-6 text-center text-gray-500">
                            Belum ada pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>

</div>

@endsection