<?php

namespace App\Features\TradingServer\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ListSecuritySymbolsRequest extends FormRequest
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
            'security_id' => [
                'required',
                'uuid',
                Rule::exists('securities', 'id')->where(
                    fn ($query) => $query->where('trading_server_id', $this->route('tradingServerUuid')),
                ),
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'alpha' => ['sometimes', 'string', 'max:255'],
            'stype' => ['sometimes', 'integer', 'min:0'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'trading_server_id' => $this->route('tradingServerUuid'),
            'security_id' => $this->route('securityUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! Str::isUuid((string) $this->route('tradingServerUuid'))) {
                $validator->errors()->add('trading_server_id', 'The TradingServer id is not a valid uuid.');
            }

            if (! Str::isUuid((string) $this->route('securityUuid'))) {
                $validator->errors()->add('security_id', 'The security id is not a valid uuid.');
            }
        });
    }
}
