<?php

namespace App\Features\Platform\Http\V1\Requests;

use App\SharedFeatures\Application\UserContext;
use Illuminate\Foundation\Http\FormRequest;

class ListPlatformsRequest extends FormRequest
{
    public function __construct(
        private readonly UserContext $userContext,
    ) {}

    public function authorize(): bool
    {
        return $this->userContext->adminCan('platform.view');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'custom_name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:255'],
            'volume_factor' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
