<?php

namespace App\Features\TradingServer\Http\V1\Requests;

use App\Features\TradingServer\Enums\EnvironmentEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class StoreTradingServerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'platform_id' => ['required', 'uuid', 'exists:platforms,id'],
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:0', 'max:65535'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'environment' => ['required', 'integer', new Enum(EnvironmentEnum::class)],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
    
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            if(!Str::isUuid($this->platform_id)) {
                $validator->errors()->add('platform_id', 'The selected platform is invalid.');
            }

            if ($validator->errors()->has('enviroment')) {
                $validator->errors()->add('enviroment', 'Check the available options at /platforms/settings/enviroments');
            }
        });
    }


    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', false),
        ]);
    }
}
