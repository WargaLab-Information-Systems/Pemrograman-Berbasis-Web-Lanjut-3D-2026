<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    /**
     * Menyesuaikan aturan validasi dari StoreBukuRequest untuk proses Update data buku.
     * Rule diwarisi dari parent (StoreBukuRequest) dengan penambahan 'sometimes'
     * agar mendukung update parsial (PATCH / PUT sebagian field).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $fieldRules) {
            if (is_array($fieldRules)) {
                $rules[$field] = array_merge(['sometimes'], $fieldRules);
            } else {
                $rules[$field] = 'sometimes|' . $fieldRules;
            }
        }

        return $rules;
    }
}
