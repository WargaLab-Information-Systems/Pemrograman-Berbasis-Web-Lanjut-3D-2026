<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'min:3', 'max:255'],
            'penulis' => ['required', 'string', 'min:3', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . now()->year],
        ];
    }
    public function messages(): array
    {
        return [
            // Pesan umum: berlaku untuk semua field
            'required' => ':Attribute wajib diisi.',
            'string'   => ':Attribute harus berupa teks.',
            'integer'  => ':Attribute harus berupa angka bulat.',

            // Pesan spesifik (format: field.rule), mengalahkan pesan umum
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid.',
            'judul.string'         => 'Judul buku harus berupa teks.',
            'judul.min'            => 'Judul buku minimal :min karakter',
            'judul.max'            => 'Judul buku maksimal :max karakter.',
            'penulis.min'          => 'Nama penulis minimal :min karakter.',
            'penulis.max'          => 'Nama penulis maksimal :max karakter.',
            'tahun_terbit.between' => 'Tahun terbit harus antara :min sampai :max',
        ];
    }
    public function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}
