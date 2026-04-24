<?php

namespace App\Features\Manager\Http\V1\Requests;

use App\Features\Manager\Enums\EnvironmentEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateManagerRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'manager_id' => ['required', 'uuid', 'exists:managers,id'],
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
            'manager_id' => $this->route('managerUuid'),
        ]);
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $managerId = $this->route('managerUuid');

            if (! Str::isUuid($managerId)) {
                $validator->errors()->add('managerId', 'Invalid manager UUID');
            }

            if ($validator->errors()->has('environment')) {
                $validator->errors()->add('environment', 'Check the available options at /platforms/settings/enviroments');
            }
        });
    }
}
