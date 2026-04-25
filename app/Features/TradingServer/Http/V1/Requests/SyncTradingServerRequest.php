<?php

namespace App\Features\TradingServer\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class SyncTradingServerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'platform_setting_id' => 'required|string|exists:platform_settings,id',
        ];
    }
    
    public function prepareForValidation(): void
    {
        $this->merge([
            'platform_setting_id' => $this->route('platform_setting_id'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (Str::isUuid($this->input('platform_setting_id'))) {
                $validator->errors()->add('platform_setting_id', 'The platform setting id is not a valid UUID.');
            }
        });
    }
}