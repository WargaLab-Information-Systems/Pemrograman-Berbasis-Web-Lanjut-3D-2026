<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    // Pakai ulang rule dari Store, tambah 'sometimes'
    // supaya update sebagian (kirim judul saja) tetap lolos.
    public function rules(): array
    {
        return collect(parent::rules())
            ->map(fn ($rule) => array_merge(['sometimes'], $rule))
            ->all();
    }
}