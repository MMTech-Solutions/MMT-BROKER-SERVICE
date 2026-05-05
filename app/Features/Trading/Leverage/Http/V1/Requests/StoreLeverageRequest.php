<?php

namespace App\Features\Trading\Leverage\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeverageRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'value' => ['required', 'integer', 'min:1'],
        ];
    }
}
