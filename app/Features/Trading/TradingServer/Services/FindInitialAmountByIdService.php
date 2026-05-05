<?php

namespace App\Features\Trading\TradingServer\Services;

use App\Features\Trading\TradingServer\DTOs\InitialAmountDTO;
use App\Features\Trading\TradingServer\Exceptions\InitialAmountNotFoundException;
use App\Features\Trading\TradingServer\Factories\InitialAmountRepositoryFactory;
use App\Features\Trading\TradingServer\Repositories\Contracts\InitialAmountRepositoryInterface;

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
