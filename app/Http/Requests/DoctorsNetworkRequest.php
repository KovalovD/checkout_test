<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorsNetworkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'specialization' => 'sometimes|string|exists:specializations,specialization',
            'min_yoe'        => 'sometimes|numeric|min:0',
            'max_yoe'        => 'sometimes|numeric|min:0',
        ];
    }
}
