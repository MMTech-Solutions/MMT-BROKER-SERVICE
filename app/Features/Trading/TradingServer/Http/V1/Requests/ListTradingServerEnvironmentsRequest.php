<?php

namespace App\Features\Trading\TradingServer\Http\V1\Requests;

use App\SharedFeatures\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;

class ListTradingServerEnvironmentsRequest extends FormRequest
{
    public function authorize(UserContext $userContext): bool
    {
        return $userContext->can('trading_server.read');
    }

    public function rules(): array
    {
        return [];
    }
}