<?php

namespace App\Features\Account\Http\V1\Controllers;

use App\Features\Account\Http\V1\Commands\CreateAccountCommand;
use App\Features\Account\Http\V1\Requests\CreateAccountRequest;
use App\Features\Account\Http\V1\Resources\AccountResource;
use App\Features\Account\UseCases\CreateAccountUseCase;
use MMT\ApiResponseNormalizer\ApiResponse;

class CreateAccountController
{
    use ApiResponse;

    public function __invoke(
        CreateAccountRequest $request,
        CreateAccountUseCase $useCase
    ) {
        $command = CreateAccountCommand::fromRequest($request);

        $account = $useCase->execute($command);

        return $this->created(AccountResource::make($account)->resolve());
    }
}
