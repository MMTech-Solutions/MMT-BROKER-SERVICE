<?php

namespace App\Features\Platform\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use MMT\LaravelFeatureScaffold\Exceptions\MmtException;

class UpdatePlatformRequest extends FormRequest
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
        $platformUuid = $this->route('platformUuid');

        if (! Str::isUuid($platformUuid)) {
            throw new MmtException('INVALID_PLATFORM_UUID', 'Invalid platform UUID', 422);
        }

        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('platforms', 'name')->ignore($platformUuid)],
            'custom_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('platforms', 'code')->ignore($platformUuid)],
            'volume_factor' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
