<?php

namespace App\SharedFeatures\Transformers;

use App\SharedFeatures\ValueObjects\Price;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class PriceTransformer implements Transformer
{
    /**
     * @param  Price  $value
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        return $value->toFloat();
    }
}
