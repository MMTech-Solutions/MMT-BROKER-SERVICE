<?php

namespace App\Features\Account\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Str;

class CreateAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'server_group_id' => ['required', 'uuid', 'exists:server_groups,id'],
            'leverage_id' => ['required', 'uuid', 'exists:leverages,id']
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if (!Str::isUuid($this->input('server_group_id'))) {
                $validator->errors()->add('server_group_id', 'The selected server group is invalid.');
            }
        });
    }
}