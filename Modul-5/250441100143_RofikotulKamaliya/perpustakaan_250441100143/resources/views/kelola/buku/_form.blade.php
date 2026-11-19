<div class="form-grid">
    <div class="form-group">
        <label for="kategori_id">Kategori</label>
        <select id="kategori_id" name="kategori_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}"
                    {{ (string) old('kategori_id', $buku->kategori_id ?? '') === (string) $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="judul">Judul Buku</label>
        <input type="text" id="judul" name="judul" value="{{ old('judul', $buku->judul ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="penulis">Penulis</label>
        <input type="text" id="penulis" name="penulis" value="{{ old('penulis', $buku->penulis ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="tahun_terbit">Tahun Terbit</label>
        <input type="number" id="tahun_terbit" name="tahun_terbit" min="1901" max="{{ date('Y') }}"
               value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}" required>
    </div>
</div>