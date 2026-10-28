<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $rule) {
            $rules[$field] = array_values(array_filter(
                (array) $rule,
                fn ($item) => $item !== 'required'
            ));
        }

        return $rules;
    }
}
