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
            'kategori_id' => 'required|integer',
            'judul' => 'required|string|min:3',
            'penulis' => 'required|string',
            'tahun_terbit' => 'required|integer|min:1900|max:2026',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'ID Kategori wajib diisi.',
            'kategori_id.integer' => 'ID Kategori harus berupa angka.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'penulis.required' => 'Penulis wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min' => 'Tahun terbit tidak boleh kurang dari 1900.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh lebih dari 2026.',
        ];
    }
}