<?php

namespace App\Features\Platform\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowPlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
