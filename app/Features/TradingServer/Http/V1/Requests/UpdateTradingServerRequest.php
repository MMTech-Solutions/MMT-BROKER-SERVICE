<?php

namespace App\Features\TradingServer\Http\V1\Requests;

use App\Features\TradingServer\Enums\EnvironmentEnum;
use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateTradingServerRequest extends FormRequest
{

    public function authorize(
        UserContext $userContext,
    ): bool
    {
        return $userContext->can('trading_server.update');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'trading_server_id' => ['required', 'uuid', 'exists:trading_servers,id'],
            'host' => ['sometimes', 'string', 'max:255'],
            'port' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'username' => ['sometimes', 'string', 'max:255'],
            'password' => ['sometimes', 'string', 'max:255'],
            'environment' => ['sometimes', 'integer', new Enum(EnvironmentEnum::class)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
    
    public function prepareForValidation()
    {
        $this->merge([
            'trading_server_id' => $this->route('tradingServerUuid'),
        ]);
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $TradingServerId = $this->route('tradingServerUuid');

            if (! Str::isUuid($TradingServerId)) {
                $validator->errors()->add('TradingServerId', 'Invalid TradingServer UUID');
            }

            if ($validator->errors()->has('environment')) {
                $validator->errors()->add('environment', 'Check the available options at /platforms/settings/enviroments');
            }
        });
    }
}
