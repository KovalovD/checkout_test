<?php

namespace App\Enums;

use App\DiscountStrategies\BogoStrategy;
use App\DiscountStrategies\BulkStrategy;
use App\DiscountStrategies\NoDiscountStrategy;
use App\Models\DiscountRule;
use App\Models\Product;

enum DiscountType: string
{
    case BOGO = 'buy_one_get_one_free';
    case BULK = 'bulk';
    case NO_DISCOUNT = 'no_discount';

    public static function countTotal(DiscountRule $rule, float $quantity, Product $product): float
    {
        return (match ($rule->type) {
            self::BOGO => new BogoStrategy($product, $quantity),
            self::BULK => new BulkStrategy($product, $quantity, $rule->discountPrice, $rule->minimumQuantity),
            self::NO_DISCOUNT => new NoDiscountStrategy($product, $quantity)
        })->countTotal();
    }
}
