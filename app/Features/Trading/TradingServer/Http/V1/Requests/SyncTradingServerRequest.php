<?php

namespace App\Features\Trading\TradingServer\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class SyncTradingServerRequest extends FormRequest
{
    public function authorize(UserContext $userContext): bool
    {
        return $userContext->can('trading_server.sync');
    }
    
    public function rules(): array
    {
        return [
            'trading_server_id' => 'required|string|exists:trading_servers,id',
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
        $validator->after(function (Validator $validator) {
            if (! Str::isUuid($this->input('trading_server_id'))) {
                $validator->errors()->add('trading_server_id', 'The trading server id is not a valid UUID.');
            }
        });
    }
}
