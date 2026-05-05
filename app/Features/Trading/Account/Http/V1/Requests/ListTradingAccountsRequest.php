<?php

namespace App\Features\Trading\Account\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;

class ListTradingAccountsRequest extends FormRequest
{
    public function authorize(UserContext $userContext): bool
    {
        return $userContext->can('account.read');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'external_user_id' => ['sometimes', 'uuid'],
            'external_trader_id' => ['sometimes', 'string', 'max:255'],
            'server_group_id' => ['sometimes', 'uuid'],
            'is_active' => ['sometimes', 'boolean'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
