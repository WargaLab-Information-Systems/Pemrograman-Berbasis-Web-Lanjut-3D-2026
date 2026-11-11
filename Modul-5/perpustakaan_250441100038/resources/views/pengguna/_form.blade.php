<div class="form-grup">
    <label for="name">Nama</label>
    <input id="name" name="name" type="text" value="{{ old('name', $pengguna->name ?? '') }}" required>
</div>
<div class="form-grup">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email', $pengguna->email ?? '') }}" required>
</div>
<div class="form-grup">
    <label for="password">Password {{ isset($pengguna) ? '(kosongkan jika tidak diubah)' : '' }}</label>
    <input id="password" name="password" type="password" {{ isset($pengguna) ? '' : 'required' }}>
</div>

@can('kelola role pengguna')
    <div class="form-grup">
        <label for="role">Role</label>
        <select id="role" name="role" required>
            @foreach ($roles as $r)
                <option value="{{ $r->name }}"
                    {{ old('role', isset($pengguna) ? $pengguna->getRoleNames()->first() : 'siswa') === $r->name ? 'selected' : '' }}>
                    {{ ucfirst($r->name) }}
                </option>
            @endforeach
        </select>
    </div>
@endcan
