<?php

namespace App\Features\Platform\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class DeletePlatformSettingRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'platform_id' => ['required', 'uuid', 'exists:platforms,id'],
            'setting_id' => ['required', 'uuid', 'exists:platform_settings,id'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'platform_id' => $this->route('platformUuid'),
            'setting_id' => $this->route('settingUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if(!Str::isUuid($this->platform_id)) {
                $validator->errors()->add('platform_id', 'The platform id is not a valid uuid.');
            }

            if(!Str::isUuid($this->setting_id)) {
                $validator->errors()->add('setting_id', 'The setting id is not a valid uuid.');
            }
        });
    }
}
