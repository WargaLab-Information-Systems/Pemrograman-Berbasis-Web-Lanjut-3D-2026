<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateBukuRequest extends StoreBukuRequest
{
    /**
     * Menyesuaikan aturan validasi dari StoreBukuRequest untuk proses Update data buku.
     * Rule diwarisi dari parent (StoreBukuRequest) dengan penambahan 'sometimes'
     * agar mendukung update parsial (PATCH / PUT sebagian field).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $fieldRules) {
            if (is_array($fieldRules)) {
                $rules[$field] = array_merge(['sometimes'], $fieldRules);
            } else {
                $rules[$field] = 'sometimes|'.$fieldRules;
            }
        }

        return $rules;
    }
}
