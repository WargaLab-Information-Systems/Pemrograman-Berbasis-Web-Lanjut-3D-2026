<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Arahkan user ke dashboard sesuai permission/role-nya. */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->can('akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->can('akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        // Siswa: tidak punya dashboard administrasi, langsung ke daftar buku
        return redirect()->route('buku.index');
    }
}
