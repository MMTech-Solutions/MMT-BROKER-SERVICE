<?php

namespace App\Features\Manager\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

/**
 * @property string $managerId
 */
class InitializeManagerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'manager_id' => ['required', 'uuid', 'exists:managers,id'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'manager_id' => $this->route('managerUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if(!Str::isUuid($this->manager_id)) {
                $validator->errors()->add('manager_id', 'The manager id is not a valid uuid.');
            }
        });
    }
}