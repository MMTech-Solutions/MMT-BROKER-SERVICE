<?php

namespace App\Features\Account\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class CreateAccountRequest extends FormRequest
{
    public function authorize(UserContext $userContext): bool
    {
        return $userContext->can('account.create');
    }

    public function rules(): array
    {
        return [
            'server_group_id' => ['required', 'uuid', 'exists:server_groups,id'],
            'leverage_id' => ['required', 'uuid', 'exists:leverages,id'],
            'amount_id' => ['sometimes', 'uuid', 'exists:initial_amounts,id'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $amountId = $this->input('amount_id') ?? null;

            if (! Str::isUuid($this->input('server_group_id'))) {
                $validator->errors()->add('server_group_id', 'The selected server group is invalid.');
            }
            if ($amountId && ! Str::isUuid($amountId)) {
                $validator->errors()->add('amount_id', 'The selected amount is invalid.');
            }
        });
    }
}
