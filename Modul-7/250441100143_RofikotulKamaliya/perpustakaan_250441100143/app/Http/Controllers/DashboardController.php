<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Arahkan pengguna ke dashboard sesuai role (dicek lewat Spatie, bukan kolom manual).
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        if ($user->hasRole('siswa')) {
            return redirect()->route('siswa.dashboard');
        }

        abort(403, 'Akun Anda belum memiliki role.');
    }
}
