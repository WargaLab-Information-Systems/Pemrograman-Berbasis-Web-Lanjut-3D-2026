<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    // Mewarisi authorize(), messages(), attributes() dari StoreBukuRequest.
    // Rule sama, hanya "required" diganti "sometimes" supaya update sebagian lolos.
    public function rules(): array
    {
        return collect(parent::rules())
            ->map(fn ($rule) => array_merge(
                ['sometimes'],
                array_values(array_diff($rule, ['required']))
            ))
            ->all();
    }
}