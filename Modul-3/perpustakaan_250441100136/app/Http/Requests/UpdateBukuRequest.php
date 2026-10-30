<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    /**
     * Izinkan request ini dijalankan.
     */
    public function authorize(): bool
    {
        return true; // Ubah nilai return menjadi true
    }

    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $fieldRules) {
            $rules[$field] = array_map(function ($rule) {
                return $rule === 'required' ? 'sometimes' : $rule;
            }, $fieldRules);
        }

        return $rules;
    }
}