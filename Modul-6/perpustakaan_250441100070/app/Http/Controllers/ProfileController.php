<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Profil SELALU diambil dari pengguna yang sedang login ($request->user()),
     * tidak ada parameter id, sehingga pengguna tidak bisa membuka profil orang lain.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'anggota' => $user->profilAnggota(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->fill(Arr::only($data, ['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Sinkronkan nama dan nomor telepon pada data keanggotaan
        $user->profilAnggota()->update([
            'nama' => $user->name,
            'no_telepon' => $data['no_telepon'] ?? null,
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
