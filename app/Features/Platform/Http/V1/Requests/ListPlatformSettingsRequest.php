<?php

namespace App\Features\Platform\Http\V1\Requests;

use App\Features\Platform\Enums\PlatformEnvironment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class ListPlatformSettingsRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'platform_id' => ['sometimes', 'string', 'uuid'],
            'host' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255'],
            'port' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'enviroment' => ['sometimes', 'integer', new Enum(PlatformEnvironment::class)],
            'is_active' => ['sometimes', 'boolean'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'platform_id' => $this->route('platformUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('enviroment')) {
                $validator->errors()->add('enviroment', 'Check the available options at /platforms/settings/enviroments');
            }

            if (!Str::isUuid($this->platform_id)) {
                $validator->errors()->add('platform_id', 'The platform id is not a valid uuid.');
            }
        });
    }
}
