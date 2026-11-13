@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="book-page">

    <!-- Page Header -->
    <div class="page-header" data-aos="fade-down" data-aos-duration="700">
        <span class="page-label">ADMINISTRASI SISTEM</span>
        <h1>👥 Manajemen Pengguna</h1>
        <p>Kelola akun dan role pengguna perpustakaan.</p>
    </div>

    <!-- Breadcrumb / back link -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-600
                  bg-white border border-indigo-200 rounded-xl hover:bg-indigo-50 transition-all duration-200 shadow-sm">
            ← Kembali ke Dashboard
        </a>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <p class="alert-success">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p class="alert-error">{{ session('error') }}</p>
    @endif
    @if ($errors->any())
        <div class="alert-error flex-col items-start gap-1">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Table Card -->
    <div class="book-form-wrapper" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">
        <h2>Daftar Pengguna Terdaftar</h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-gray-400 font-mono text-xs">{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-gray-500">{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    @php
                                        $badgeClass = match($role->name) {
                                            'admin'   => 'bg-red-100 text-red-700 border-red-200',
                                            'petugas' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'siswa'   => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            default   => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                        $badgeIcon = match($role->name) {
                                            'admin'   => '🛡️',
                                            'petugas' => '📚',
                                            'siswa'   => '🎓',
                                            default   => '👤',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                        {{ $badgeIcon }} {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if (auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.updateRole', $user->id) }}"
                                          method="POST"
                                          class="flex items-center gap-2 flex-wrap">
                                        @csrf
                                        @method('PUT')

                                        <select name="role"
                                                required
                                                class="text-sm border border-gray-200 rounded-lg px-3 py-2
                                                       focus:outline-none focus:ring-2 focus:ring-indigo-500
                                                       bg-white text-gray-700 cursor-pointer">
                                            <option value="admin"   @selected($user->hasRole('admin'))>🛡️ Admin</option>
                                            <option value="petugas" @selected($user->hasRole('petugas'))>📚 Petugas</option>
                                            <option value="siswa"   @selected($user->hasRole('siswa'))>🎓 Siswa</option>
                                        </select>

                                        <button type="submit"
                                                class="px-4 py-2 text-xs font-semibold text-white rounded-lg
                                                       transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                style="background: linear-gradient(135deg, #4F46E5, #3B82F6);">
                                            Simpan
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-indigo-500 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">
                                        👋 Akun Anda
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">👥</span>
                                    <p>Belum ada pengguna terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
