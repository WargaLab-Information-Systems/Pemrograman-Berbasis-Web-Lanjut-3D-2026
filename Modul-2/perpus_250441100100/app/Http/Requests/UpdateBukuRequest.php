<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $fieldRules) {
            $rules[$field] = array_map(
                function ($rule) {
                    return $rule === 'required'
                        ? 'sometimes|required'
                        : $rule;
                },
                $fieldRules
            );
        }

        return $rules;
    }
}
