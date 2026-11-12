
<x-admin-layout>

    {{-- JUDUL HALAMAN --}}
    <h1 class="admin-page-title">
        Manajemen Pengguna
    </h1>

    @if ($editingUser)
        <div class="admin-information" style="margin-top: 0; margin-bottom: 20px;">
            <h2 class="admin-section-title">Formulir Edit Pengguna</h2>
            <form action="{{ route('admin.users.update', $editingUser) }}" method="POST" class="admin-inline-form">
                @csrf
                @method('PUT')
                <input name="_form" type="hidden" value="edit">
                <div class="admin-form-grid">
                    <div>
                        <label for="edit-name">Nama Pengguna</label>
                        <input id="edit-name" name="name" value="{{ old('name', $editingUser->name) }}" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit-email">Email</label>
                        <input id="edit-email" name="email" type="email" value="{{ old('email', $editingUser->email) }}" required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit-role">Pilih Role</label>
                        <select id="edit-role" name="role" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role', $editingUser->getRoleNames()->first()) === $role->name) @disabled($editingUser->is(auth()->user()) && $role->name !== 'admin')>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @if ($editingUser->is(auth()->user()))
                    <p class="form-error" style="margin-top: 12px;">Demi keamanan sistem, role akun admin yang sedang digunakan tidak dapat diubah.</p>
                @endif
                <div class="admin-form-actions">
                    <button type="submit" class="admin-form-submit">Simpan Perubahan</button>
                    <a href="{{ route('admin.users.index') }}" class="admin-form-back">Batal</a>
                </div>
            </form>
        </div>
    @endif

    @if ((request()->boolean('tambah') || old('_form') === 'create') && ! $editingUser)
        <div class="admin-information" style="margin-top: 0; margin-bottom: 20px;">
            <h2 class="admin-section-title">Formulir Tambah Pengguna</h2>
            <form action="{{ route('admin.users.store') }}" method="POST" class="admin-inline-form">
                @csrf
                <input name="_form" type="hidden" value="create">
                <div class="admin-form-grid">
                    <div>
                        <label for="name">Nama Pengguna</label>
                        <input id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password">Kata Sandi</label>
                        <input id="password" name="password" type="password" minlength="8" required>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="role">Pilih Role</label>
                        <select id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role') === $role->name)>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="admin-form-submit">Simpan Pengguna</button>
                    <a href="{{ route('admin.users.index') }}" class="admin-form-back">Kembali</a>
                </div>
            </form>
        </div>
    @endif

    {{-- TOMBOL KEMBALI --}}
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 font-semibold text-red-800 hover:text-red-950">
            <span aria-hidden="true" class="text-xl">&larr;</span> Kembali
        </a>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="admin-information" style="margin-top: 0;">

        {{-- JUDUL DAN TOMBOL TAMBAH --}}
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; margin-bottom: 25px;">

            <h3 class="admin-section-title" style="margin-bottom: 0;">
                Daftar Seluruh Pengguna
            </h3>

            <a href="{{ route('admin.users.index', ['tambah' => 1]) }}"
               class="admin-add-button">
                + Tambah Pengguna
            </a>

        </div>

        {{-- NOTIFIKASI BERHASIL --}}
        @if (session('success'))
            <div style="background-color: #dcfce7; color: #166534; padding: 12px; margin-bottom: 16px; border-radius: 6px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- NOTIFIKASI GAGAL --}}
        @if (session('error'))
            <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; margin-bottom: 16px; border-radius: 6px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABEL PENGGUNA --}}
        <div style="overflow-x: auto;">

            <table style="width: 100%; border-collapse: collapse; background-color: white;">

                <thead>
                    <tr style="background-color: #f1f5f9;">
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">No</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Nama</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Email</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Role</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $user)

                        <tr>

                            <td style="border: 1px solid #e2e8f0; padding: 13px; text-align: center;">
                                {{ $loop->iteration }}
                            </td>

                            <td style="border: 1px solid #e2e8f0; padding: 13px;">
                                {{ $user->name }}
                            </td>

                            <td style="border: 1px solid #e2e8f0; padding: 13px;">
                                {{ $user->email }}
                            </td>

                            <td style="border: 1px solid #e2e8f0; padding: 13px;">
                                {{ $user->getRoleNames()->implode(', ') }}
                            </td>

                            <td style="border: 1px solid #e2e8f0; padding: 13px;">

                                <div style="display: flex; gap: 8px; align-items: center;">

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.users.index', ['edit' => $user->id]) }}"
                                       style="background-color: #2563eb; color: white; padding: 7px 12px; border-radius: 5px; text-decoration: none;">
                                        Edit
                                    </a>

                                    {{-- HAPUS --}}
                                    @if ($user->id !== auth()->id())

                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                style="background-color: #dc2626; color: white; padding: 7px 12px; border-radius: 5px; border: none; cursor: pointer;">
                                                Hapus
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="border: 1px solid #e2e8f0; padding: 20px; text-align: center;">
                                Belum ada pengguna.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-admin-layout>
