<?php

namespace App\SharedFeatures\Transformers;

use App\SharedFeatures\ValueObjects\PositiveMoney;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyTransformer implements Transformer
{
    /**
     * @param  PositiveMoney  $value
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        return $value->toFloat();
    }
}
