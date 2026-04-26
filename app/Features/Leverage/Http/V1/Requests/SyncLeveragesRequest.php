<?php

namespace App\Features\Leverage\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncLeveragesRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'leverage_ids'   => ['required', 'array'],
            'leverage_ids.*' => ['required', 'uuid', 'exists:leverages,id'],
        ];
    }
}
