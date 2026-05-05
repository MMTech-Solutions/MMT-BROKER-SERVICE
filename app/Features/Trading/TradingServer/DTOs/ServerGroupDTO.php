<?php

namespace App\Features\Trading\TradingServer\DTOs;

use App\Features\Trading\TradingServer\Enums\EnvironmentEnum;
use App\Features\Trading\TradingServer\Models\ServerGroup;
use App\SharedFeatures\Transformers\MoneyTransformer;
use App\SharedFeatures\Transformers\PriceTransformer;
use App\SharedFeatures\ValueObjects\Currency;
use App\SharedFeatures\ValueObjects\Number;
use App\SharedFeatures\ValueObjects\PositiveNumber;
use App\SharedFeatures\ValueObjects\Price;
use Mmt\TradingServiceSdk\Enums\PlatformEnum;
use Mmt\TradingServiceSdk\Platforms\MT5\Enums\TransactionTypeEnum;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class ServerGroupDTO extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $metaName,
        public readonly string $tradingServerId,
        public readonly ?string $description,

        #[WithTransformer(PriceTransformer::class)]
        public readonly ?Price $minDeposit,

        #[WithTransformer(PriceTransformer::class)]
        public readonly ?Price $minWithdrawal,

        public readonly int $accountLimits,

        #[WithTransformer(MoneyTransformer::class)]
        public readonly PositiveNumber $defaultAmount,

        public readonly ?TransactionTypeEnum $defaultAmountType,
        public readonly int $currencyDenominationFactor,
        public readonly bool $isPrivate,
        public readonly bool $isDefault,
        public readonly bool $isActive,
        public readonly bool $isDepositEnabled,
        public readonly bool $isWithdrawalEnabled,
        public readonly bool $useCountriesRestrictions,
        public readonly ?array $restrictedCountries,
        public readonly string $connectionId,
        public readonly EnvironmentEnum $environment,
        public readonly PlatformEnum $platform,
    ) {}

    public static function fromModel(ServerGroup $serverGroup): self
    {
        $currency = new Currency('USD', 2);
        $minDeposit = null;
        $minWithdrawal = null;

        if ($serverGroup->min_deposit > 0) {
            $minDepositAmount = PositiveNumber::fromInt($serverGroup->min_deposit);
            $minDeposit = new Price($minDepositAmount, $currency);
        }
        if ($serverGroup->min_withdrawal > 0) {
            $minWithdrawalAmount = PositiveNumber::fromInt($serverGroup->min_withdrawal);
            $minWithdrawal = new Price($minWithdrawalAmount, $currency);
        }
        
        $defaultAmount = PositiveNumber::fromInt($serverGroup->default_amount);

        return new self(
            id: $serverGroup->id,
            name: $serverGroup->name,
            metaName: $serverGroup->meta_name,
            tradingServerId: $serverGroup->trading_server_id,
            description: $serverGroup->description,

            minDeposit: $minDeposit,
            minWithdrawal: $minWithdrawal,
            defaultAmount: $defaultAmount,
            defaultAmountType: $serverGroup->default_amount_type,
            currencyDenominationFactor: $serverGroup->currency_denomination_factor,
            accountLimits: $serverGroup->account_limits,
            isPrivate: $serverGroup->is_private,
            isDefault: $serverGroup->is_default,
            isActive: $serverGroup->is_active,
            isDepositEnabled: $serverGroup->is_deposit_enabled,
            isWithdrawalEnabled: $serverGroup->is_withdrawal_enabled,
            useCountriesRestrictions: $serverGroup->use_countries_restrictions,
            restrictedCountries: $serverGroup->restricted_countries,
            connectionId: $serverGroup->tradingServer->connection_id,
            environment: $serverGroup->tradingServer->environment,
            platform: $serverGroup->tradingServer->platform->toEnum(),
        );
    }
}
