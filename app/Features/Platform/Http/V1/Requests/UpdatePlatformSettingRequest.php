<?php

namespace App\Features\Platform\Http\V1\Requests;

use App\Features\Platform\Enums\PlatformEnviroment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class UpdatePlatformSettingRequest extends FormRequest
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
        $settingUuid = $this->route('settingUuid');

        if (! Str::isUuid($settingUuid)) {
            throw new MmtException('INVALID_SETTING_UUID', 'Invalid setting UUID', 422);
        }

        return [
            'host' => ['sometimes', 'string', 'max:255'],
            'port' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'username' => ['sometimes', 'string', 'max:255'],
            'password' => ['sometimes', 'string', 'max:255'],
            'enviroment' => ['sometimes', 'integer', new Enum(PlatformEnviroment::class)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
