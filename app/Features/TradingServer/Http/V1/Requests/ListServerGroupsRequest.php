<?php

namespace App\Features\TradingServer\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class ListServerGroupsRequest extends FormRequest
{
    public function authorize(UserContext $userContext): bool
    {
        return $userContext->can('trading_server.read');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'trading_server_id' => ['required', 'uuid', 'exists:trading_servers,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'meta_name' => ['sometimes', 'string', 'max:255'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'trading_server_id' => $this->route('tradingServerUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! Str::isUuid((string) $this->route('tradingServerUuid'))) {
                $validator->errors()->add('trading_server_id', 'The TradingServer id is not a valid uuid.');
            }
        });
    }
}
