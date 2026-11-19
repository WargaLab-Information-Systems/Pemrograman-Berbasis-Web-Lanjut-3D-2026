<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Kategori</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamans as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ $p->buku->kategori->nama ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                    <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td>
                        <a href="{{ route('peminjaman.show', $p->id) }}">Lihat</a>

                        @if ($p->status === 'diajukan')
                            <form action="{{ route('peminjaman.batal', $p->id) }}" method="POST" class="inline-form"
                                  onsubmit="return confirm('Batalkan pengajuan peminjaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Batalkan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
