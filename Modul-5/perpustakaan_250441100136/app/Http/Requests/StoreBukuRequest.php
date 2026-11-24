<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pastikan ini bernilai true
    }

    public function rules(): array
    {
        return [
            'kategori' => 'required',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|digits:4',
        ];
    }
}
