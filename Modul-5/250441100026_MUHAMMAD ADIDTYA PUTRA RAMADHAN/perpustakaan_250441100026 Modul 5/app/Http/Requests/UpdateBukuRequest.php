<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
