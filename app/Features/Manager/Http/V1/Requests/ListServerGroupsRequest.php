<?php

namespace App\Features\Manager\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class ListServerGroupsRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'manager_id' => ['required', 'uuid', 'exists:managers,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'meta_name' => ['sometimes', 'string', 'max:255'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
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
        $validator->after(function (Validator $validator): void {
            if (! Str::isUuid((string) $this->route('managerUuid'))) {
                $validator->errors()->add('manager_id', 'The manager id is not a valid uuid.');
            }
        });
    }
}
