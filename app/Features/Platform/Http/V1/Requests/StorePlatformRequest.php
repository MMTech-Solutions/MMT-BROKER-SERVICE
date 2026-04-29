<?php

namespace App\Features\Platform\Http\V1\Requests;

use App\SharedFeatures\Application\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;

class StorePlatformRequest extends FormRequest
{
    public function __construct(
        private readonly UserContext $userContext,
    ) {}

    public function authorize(): bool
    {
        return $this->userContext->adminCan('platform.create');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:platforms,name'],
            'custom_name' => ['nullable', 'string', 'max:255'],
            'volume_factor' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $platformName = $this->input('name');
            if(!in_array(strtolower($platformName), PlatformEnum::toLowerString())) {
                $validator->errors()->add('name', 'The selected platform is invalid.');
                $validator->errors()->add('name', 'Check the available options at /platforms/availables');
            }
        });
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', false)
        ]);
    }
}
