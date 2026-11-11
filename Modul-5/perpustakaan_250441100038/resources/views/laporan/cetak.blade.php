<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perpustakaan</title>
    <style>
        body { font-family: 'Times New Roman', serif; color: #000; margin: 0; padding: 30px; font-size: 13px; }
        .aksi { background: #f3f6f4; padding: 12px 16px; margin: -30px -30px 25px; border-bottom: 1px solid #ddd; font-family: 'Segoe UI', sans-serif; }
        .aksi button, .aksi a { background: #2f7a4c; color: #fff; border: 0; padding: 8px 16px; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; margin-right: 6px; }
        .aksi a { background: #6c757d; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 18px; }
        .kop h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .kop p { margin: 3px 0 0; }
        h2 { font-size: 15px; margin: 22px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background: #e5e5e5; }
        .ringkas td:first-child { width: 60%; }
        .ttd { margin-top: 40px; width: 100%; display: flex; justify-content: flex-end; }
        .ttd div { text-align: center; width: 250px; }
        .ttd .garis { margin-top: 70px; border-top: 1px solid #000; padding-top: 4px; font-weight: bold; }
        tr { page-break-inside: avoid; }
        @media print { .aksi { display: none; } body { padding: 0; } @page { size: A4; margin: 18mm; } }
    </style>
</head>
<body>
    <div class="aksi">
        <button onclick="window.print()"> Cetak / Simpan PDF</button>
        <a href="{{ route('laporan.index', $filter) }}">Kembali</a>
    </div>

    <div class="kop">
        <h1>Laporan Perpustakaan Digital</h1>
        <p>
            Periode:
            {{ !empty($filter['dari']) ? \Carbon\Carbon::parse($filter['dari'])->format('d-m-Y') : 'Awal' }}
            s.d.
            {{ !empty($filter['sampai']) ? \Carbon\Carbon::parse($filter['sampai'])->format('d-m-Y') : 'Sekarang' }}
            @if (!empty($filter['status'])) &mdash; Status: {{ ucfirst($filter['status']) }} @endif
        </p>
        <p>Dicetak pada {{ now()->format('d-m-Y H:i') }} oleh {{ auth()->user()->name }}</p>
    </div>

    <h2>A. Ringkasan</h2>
    <table class="ringkas">
        @foreach ($ringkasan as $label => $nilai)
            <tr><td>{{ $label }}</td><td>{{ $nilai }}</td></tr>
        @endforeach
    </table>

    <h2>B. Data Peminjaman</h2>
    <table>
        <thead><tr><th>No</th><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->user->name ?? $p->anggota->nama ?? '-' }}</td>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>C. Jumlah Buku per Kategori</h2>
    <table>
        <thead><tr><th>Kategori</th><th>Jumlah Buku</th></tr></thead>
        <tbody>@foreach ($perKategori as $k)<tr><td>{{ $k->nama }}</td><td>{{ $k->bukus_count }}</td></tr>@endforeach</tbody>
    </table>

    <h2>D. Buku Paling Sering Dipinjam</h2>
    <table>
        <thead><tr><th>Judul</th><th>Jumlah Peminjaman</th></tr></thead>
        <tbody>@foreach ($populer as $b)<tr><td>{{ $b->judul }}</td><td>{{ $b->peminjamans_count }}</td></tr>@endforeach</tbody>
    </table>

    <div class="ttd">
        <div>
            Mengetahui,<br>
            @role('admin') Admin Perpustakaan @endrole
            @role('petugas') Petugas Perpustakaan @endrole
            <div class="garis">{{ auth()->user()->name }}</div>
        </div>
    </div>
</body>
</html>
