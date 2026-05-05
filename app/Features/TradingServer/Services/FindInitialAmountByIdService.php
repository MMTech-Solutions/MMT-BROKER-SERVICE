<?php

namespace App\Features\TradingServer\Services;

use App\Features\TradingServer\DTOs\InitialAmountDTO;
use App\Features\TradingServer\Exceptions\InitialAmountNotFoundException;
use App\Features\TradingServer\Factories\InitialAmountRepositoryFactory;
use App\Features\TradingServer\Repositories\Contracts\InitialAmountRepositoryInterface;

class FindInitialAmountByIdService
{
    private InitialAmountRepositoryInterface $initialAmountRepository;

    public function __construct(
        private readonly InitialAmountRepositoryFactory $initialAmountRepositoryFactory,
    ) {
        $this->initialAmountRepository = $initialAmountRepositoryFactory->make();
    }

    public function execute(string $id): InitialAmountDTO
    {
        $initialAmount = $this->initialAmountRepository->findById($id) ?? throw new InitialAmountNotFoundException;

        return InitialAmountDTO::from($initialAmount);
    }
}
