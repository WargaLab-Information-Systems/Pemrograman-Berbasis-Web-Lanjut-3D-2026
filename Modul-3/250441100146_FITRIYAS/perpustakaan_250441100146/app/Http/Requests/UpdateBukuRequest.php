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
                    if ($rule === 'required') {
                        return 'sometimes';
                    }

                    return $rule;
                },
                $fieldRules
            );
        }

        return $rules;
    }
}
