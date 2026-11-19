@props(['status'])

@php
    $map = [
        'diajukan'     => ['Diajukan', 'bg-blue-100 text-blue-700'],
        'dipinjam'     => ['Dipinjam', 'bg-amber-100 text-amber-700'],
        'dikembalikan' => ['Dikembalikan', 'bg-green-100 text-green-700'],
        'ditolak'      => ['Ditolak', 'bg-red-100 text-red-700'],
    ];
    [$label, $kelas] = $map[$status] ?? [ucfirst((string) $status), 'bg-gray-100 text-gray-700'];
@endphp

<span class="{{ $kelas }} text-xs font-semibold px-2.5 py-1 rounded-lg">{{ $label }}</span>
