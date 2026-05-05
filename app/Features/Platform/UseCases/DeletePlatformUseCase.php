<?php

namespace App\Features\Platform\UseCases;

use App\Features\Platform\Exceptions\PlatformHasDependenciesException;
use App\Features\Platform\Exceptions\PlatformNotFoundException;
use App\Features\Platform\Factories\PlatformRepositoryFactory;
use App\Features\Platform\Http\V1\Commands\DeletePlatformCommand;
use App\Features\Platform\Repositories\Contracts\PlatformRepositoryInterface;
use Illuminate\Database\QueryException;

class DeletePlatformUseCase
{
    protected PlatformRepositoryInterface $platformRepository;
    
    public function __construct(
        private readonly PlatformRepositoryFactory $platformRepositoryFactory,
    ){
        $this->platformRepository = $platformRepositoryFactory->make();
    }

    public function execute(DeletePlatformCommand $command): void
    {
        $platform = $this->platformRepository->findById($command->platformId)
            ?? throw new PlatformNotFoundException();

        try {
            $this->platformRepository->deleteById($platform->id);
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
