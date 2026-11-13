<?php

namespace App\Http\Requests;

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
     * Mewarisi semua rule dari StoreBukuRequest dan menambahkan 'sometimes'
     * agar field tidak wajib dikirim semua saat update (PATCH).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $aturan) {
            $rules[$field] = array_merge(['sometimes'], $aturan);
        }

        return $rules;
    }
}
