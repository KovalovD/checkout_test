<?php

namespace App\Models;

use App\Enums\DiscountType;

class DiscountRule
{
    public function __construct(
        public DiscountType $type,
        public float $minimumQuantity = 0,
        public float $discountPrice = 0
    ) {
    }
}
