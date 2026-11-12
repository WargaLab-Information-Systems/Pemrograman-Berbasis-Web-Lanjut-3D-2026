<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
   
    public function rules(): array
    {
        return collect(parent::rules())
            ->map(fn (array $rules) => array_merge(
                ['sometimes'],
                array_values(array_diff($rules, ['required']))
            ))
            ->all();
    }
}
