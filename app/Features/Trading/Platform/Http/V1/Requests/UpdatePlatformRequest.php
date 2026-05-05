<?php

namespace App\Features\Trading\Platform\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdatePlatformRequest extends FormRequest
{
    public function __construct(
        private readonly UserContext $userContext,
    ) {}

    public function authorize(): bool
    {
        return $this->userContext->can('platform.update');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $platformUuid = $this->route('platformUuid');

        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('platforms', 'name')->ignore($platformUuid)],
            'custom_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'volume_factor' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $platformUuid = $this->route('platformUuid');

            if (! Str::isUuid($platformUuid)) {
                $validator->errors()->add('platformUuid', 'Invalid platform UUID');
            }
        });
    }
}
