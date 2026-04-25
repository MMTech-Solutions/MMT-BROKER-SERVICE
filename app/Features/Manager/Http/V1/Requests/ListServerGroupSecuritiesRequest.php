<?php

namespace App\Features\Manager\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ListServerGroupSecuritiesRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'manager_id' => ['required', 'uuid', 'exists:managers,id'],
            'server_group_id' => [
                'required',
                'uuid',
                Rule::exists('server_groups', 'id')->where(
                    fn ($query) => $query->where('manager_id', $this->route('managerUuid')),
                ),
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'manager_id' => $this->route('managerUuid'),
            'server_group_id' => $this->route('serverGroupUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! Str::isUuid((string) $this->route('managerUuid'))) {
                $validator->errors()->add('manager_id', 'The manager id is not a valid uuid.');
            }

            if (! Str::isUuid((string) $this->route('serverGroupUuid'))) {
                $validator->errors()->add('server_group_id', 'The server group id is not a valid uuid.');
            }
        });
    }
}
