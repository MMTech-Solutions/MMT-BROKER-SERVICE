<?php

namespace App\Features\Leverage\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListLeveragesRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string', 'max:255'],
            'value'    => ['sometimes', 'integer', 'min:1'],
            'page'     => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
