<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBukuRequest extends StoreBukuRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        return [
            'kategori_id' => 'sometimes|required|exists:kategoris,id',
            'judul' => 'sometimes|required|string|min:3|max:150',
            'penulis' => 'sometimes|required|string|min:3|max:100',
            'tahun_terbit' => 'sometimes|required|integer|min:1900|max:' . date('Y'),
        ];
    }
}
