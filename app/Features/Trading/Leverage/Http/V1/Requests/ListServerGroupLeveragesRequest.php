<?php

namespace App\Features\Trading\Leverage\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ListServerGroupLeveragesRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'trading_server_id' => ['required', 'uuid', 'exists:trading_servers,id'],
            'server_group_id'   => [
                'required',
                'uuid',
                Rule::exists('server_groups', 'id')->where(
                    fn ($query) => $query->where('trading_server_id', $this->route('tradingServerUuid')),
                ),
            ],
            'name'     => ['sometimes', 'string', 'max:255'],
            'value'    => ['sometimes', 'integer', 'min:1'],
            'page'     => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'trading_server_id' => $this->route('tradingServerUuid'),
            'server_group_id'   => $this->route('serverGroupUuid'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! Str::isUuid((string) $this->route('tradingServerUuid'))) {
                $validator->errors()->add('trading_server_id', 'The trading server id is not a valid uuid.');
            }

            if (! Str::isUuid((string) $this->route('serverGroupUuid'))) {
                $validator->errors()->add('server_group_id', 'The server group id is not a valid uuid.');
            }
        });
    }
}
