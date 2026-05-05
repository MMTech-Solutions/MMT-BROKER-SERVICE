<?php

namespace App\Features\Platform\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;

class ShowPlatformRequest extends FormRequest
{
    public function __construct(
        private readonly UserContext $userContext,
    ) {}

    public function authorize(): bool
    {
        return $this->userContext->can('platform.read');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
