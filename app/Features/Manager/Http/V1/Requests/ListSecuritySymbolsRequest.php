<?php

namespace App\Features\Manager\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ListSecuritySymbolsRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'manager_id' => ['required', 'uuid', 'exists:managers,id'],
            'security_id' => [
                'required',
                'uuid',
                Rule::exists('securities', 'id')->where(
                    fn ($query) => $query->where('manager_id', $this->route('managerUuid')),
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
            'manager_id' => $this->route('managerUuid'),
            'security_id' => $this->route('securityUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! Str::isUuid((string) $this->route('managerUuid'))) {
                $validator->errors()->add('manager_id', 'The manager id is not a valid uuid.');
            }

            if (! Str::isUuid((string) $this->route('securityUuid'))) {
                $validator->errors()->add('security_id', 'The security id is not a valid uuid.');
            }
        });
    }
}
