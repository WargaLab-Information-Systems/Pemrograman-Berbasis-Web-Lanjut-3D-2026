@extends('layouts.main')

@section('title', 'Kelola Pengguna & Role')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800">👥 Kelola Pengguna & Role</h3>
            <p class="text-sm text-gray-600">Daftar pengguna beserta penetapan role Spatie Laravel Permission.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border p-3 text-sm font-semibold text-gray-700">Nama</th>
                    <th class="border p-3 text-sm font-semibold text-gray-700">Email</th>
                    <th class="border p-3 text-sm font-semibold text-gray-700">Role Spatie</th>
                    <th class="border p-3 text-sm font-semibold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="border p-3 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="border p-3 text-gray-600">{{ $user->email }}</td>
                    <td class="border p-3">
                        @forelse($user->roles as $role)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 mr-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @empty
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                Tanpa Role
                            </span>
                        @endforelse
                    </td>
                    <td class="border p-3 text-center">
                        @can('kelola role pengguna')
                            <form method="POST" action="{{ route('users.update', $user->id) }}" class="inline-flex items-center gap-1">
                                @csrf
                                @method('PUT')
                                <select name="role" class="text-xs border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">
                                    @foreach($roles as $roleOption)
                                        <option value="{{ $roleOption->name }}" {{ $user->hasRole($roleOption->name) ? 'selected' : '' }}>
                                            {{ ucfirst($roleOption->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-2 py-1 bg-amber-500 text-white text-xs rounded hover:bg-amber-600 transition">
                                    Simpan
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">Read-only</span>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="border p-4 text-center text-gray-500">
                        Belum ada data pengguna terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
