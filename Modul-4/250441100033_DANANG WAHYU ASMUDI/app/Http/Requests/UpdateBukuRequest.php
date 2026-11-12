<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $rule) {
            if (is_string($rule)) {
                $rules[$field] = str_replace('required', 'sometimes|required', $rule);
            } elseif (is_array($rule)) {
                $rules[$field] = array_map(fn($r) => $r === 'required' ? 'sometimes|required' : $r, $rule);
            }
        }

        return $rules;
    }
}