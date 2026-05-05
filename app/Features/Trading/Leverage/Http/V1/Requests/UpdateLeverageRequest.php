<?php

namespace App\Features\Trading\Leverage\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeverageRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'  => ['sometimes', 'string', 'max:255'],
            'value' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
