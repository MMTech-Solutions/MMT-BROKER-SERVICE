<?php

namespace App\Features\Trading\TradingServer\Http\V1\Requests;

use App\Features\Trading\TradingServer\Enums\EnvironmentEnum;
use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class ListTradingServersRequest extends FormRequest
{

    public function authorize(
        UserContext $userContext,
    ): bool
    {
        return $userContext->can('trading_server.read');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'platform_id' => ['sometimes', 'string', 'uuid'],
            'host' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255'],
            'port' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'enviroment' => ['sometimes', 'integer', new Enum(EnvironmentEnum::class)],
            'is_active' => ['sometimes', 'boolean'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {

            $platformId = $this->route('platformUuid');

            if ($validator->errors()->has('enviroment')) {
                $validator->errors()->add('enviroment', 'Check the available options at /platforms/settings/enviroments');
            }

            if (isset($platformId) && !Str::isUuid($platformId)) {
                $validator->errors()->add('platform_id', 'The platform id is not a valid uuid.');
            }
        });
    }
}
