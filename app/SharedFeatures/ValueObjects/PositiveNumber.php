<?php declare(strict_types=1);

namespace App\SharedFeatures\ValueObjects;

use Illuminate\Support\Facades\Validator;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class PositiveNumber implements Castable, NumericValueInterface
{
    use NumericValueTrait;

    public function __construct(
        private readonly int|float $value,
    ) {
        Validator::validate([
            'value' => $this->value,
        ], [
            'value' => 'required|numeric|gte:0',
        ]);
    }
    
    public static function dataCastUsing(array $arguments = []): Cast
    {
        return new class implements Cast
        {
            public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): PositiveNumber
            {
                return new PositiveNumber($value);
            }
        };
    }
}
