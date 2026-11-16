<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|between:1900,2026',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'judul.required' => 'Judul wajib diisi.',
            'judul.min' => 'Judul minimal 3 karakter.',
            'penulis.required' => 'Penulis wajib diisi.',
            'penulis.min' => 'Penulis minimal 3 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun harus berupa angka.',
            'tahun_terbit.between' => 'Tahun harus antara 1900 sampai 2026.',
        ];
    }
}
