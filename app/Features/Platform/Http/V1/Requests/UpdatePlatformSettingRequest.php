<?php

namespace App\Features\Platform\Http\V1\Requests;

use App\Features\Platform\Enums\PlatformEnvironment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdatePlatformSettingRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'host' => ['sometimes', 'string', 'max:255'],
            'port' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'username' => ['sometimes', 'string', 'max:255'],
            'password' => ['sometimes', 'string', 'max:255'],
            'environment' => ['sometimes', 'integer', new Enum(PlatformEnvironment::class)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $settingUuid = $this->route('settingUuid');
            $platformUuid = $this->route('platformUuid');

            if (! Str::isUuid($settingUuid)) {
                $validator->errors()->add('settingUuid', 'Invalid setting UUID');
            }

            if (! Str::isUuid($platformUuid)) {
                $validator->errors()->add('platformUuid', 'Invalid platform UUID');
            }

            if ($validator->errors()->has('environment')) {
                $validator->errors()->add('environment', 'Check the available options at /platforms/settings/enviroments');
            }
        });
    }
}
