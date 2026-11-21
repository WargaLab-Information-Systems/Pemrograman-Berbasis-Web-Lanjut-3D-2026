<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; color: #333; }
        .navbar { max-width: 1000px; margin: 0 auto 20px auto; background: #fff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .navbar a { text-decoration: none; color: #007bff; font-weight: bold; }
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #dee2e6; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #343a40; color: #fff; }
        .badge { padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-info { background-color: #17a2b8; color: #fff; }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-danger { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="@if(auth()->user()->hasRole('admin')) {{ url('/admin/dashboard') }} @else {{ url('/petugas/dashboard') }} @endif">&larr; Kembali ke Dashboard</a>
        <span>{{ auth()->user()->name }}</span>
    </div>

    <div class="container">
        <h3>Laporan Peminjaman dan Pengembalian Buku</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_peminjam ?? '-' }}</td>
                        <td>{{ $item->judul_buku ?? '-' }}</td>
                        <td>{{ $item->tanggal_pinjam ?? $item->created_at }}</td>
                        <td>{{ $item->tanggal_kembali ?? '-' }}</td>
                        <td>
                            @if($item->status == 'Menunggu Persetujuan')
                                <span class="badge badge-warning">{{ $item->status }}</span>
                            @elseif($item->status == 'Disetujui')
                                <span class="badge badge-info">{{ $item->status }}</span>
                            @elseif($item->status == 'Dikembalikan')
                                <span class="badge badge-success">{{ $item->status }}</span>
                            @else
                                <span class="badge badge-danger">{{ $item->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada data laporan sirkulasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>