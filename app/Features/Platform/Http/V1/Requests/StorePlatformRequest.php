<?php

namespace App\Features\Platform\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatformRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255', 'unique:platforms,name'],
            'custom_name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:platforms,code'],
            'volume_factor' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', false)
        ]);
    }
}
