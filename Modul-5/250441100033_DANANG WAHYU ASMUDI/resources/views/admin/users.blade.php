<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pengelolaan Akun & Penetapan Role</h1>
                <p class="text-gray-600 text-sm mt-1">Khusus Administrator: Anda dapat melihat seluruh pengguna dan menetapkan role hak akses.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">No</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Nama Pengguna</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Role Saat Ini</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600">Tetapkan Role Baru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $index => $u)
                        @php
                            $currentRole = $u->roles->pluck('name')->first() ?? 'Belum ada role';
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $u->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $u->email }}</td>
                            <td class="px-4 py-3">
                                @if($currentRole === 'admin')
                                    <span class="bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full text-xs font-semibold">Admin</span>
                                @elseif($currentRole === 'petugas')
                                    <span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-semibold">Petugas</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full text-xs font-semibold">Siswa</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.users.updateRole', $u->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <select name="role" class="text-xs rounded border-gray-300 py-1 px-2 border bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @foreach($roles as $r)
                                            <option value="{{ $r->name }}" {{ $currentRole === $r->name ? 'selected' : '' }}>
                                                {{ ucfirst($r->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded font-medium transition">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

