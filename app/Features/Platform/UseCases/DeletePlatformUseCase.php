<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformHasDependenciesException;
use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformFactory;
use App\Features\Platform\Http\V1\Commands\DeletePlatformCommand;
use Illuminate\Database\QueryException;

class DeletePlatformUseCase
{
    public function __construct(
        private readonly PlatformFactory $platformFactory,
    ) {}

    public function execute(DeletePlatformCommand $command): void
    {
        $repository = $this->platformFactory->make();
        $platform = $repository->findById($command->platformId);

        if ($platform === null) {
            throw new PlatformNotFoundException;
        }

        try {
            $repository->delete($platform);
        } catch (QueryException $exception) {
            if ($this->isForeignKeyConstraintError($exception)) {
                throw new PlatformHasDependenciesException($exception);
            }

            throw $exception;
        }
    }

    private function isForeignKeyConstraintError(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $driverCode = $exception->errorInfo[1] ?? null;

        if ($sqlState === '23000' && (int) $driverCode === 1451) {
            return true;
        }

        if ($sqlState === '23503') {
            return true;
        }

        return str_contains(strtolower($exception->getMessage()), 'foreign key constraint');
    }
}
