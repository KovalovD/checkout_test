<?php

namespace App\DiscountStrategies;

use App\Models\Product;

readonly class BulkStrategy implements Discountable
{

    public function __construct(
        private Product $product,
        private float $quantity,
        private float $discountPrice,
        private float $minimumQuantity
    ) {
    }

    public function countTotal(): float
    {
        $price = $this->quantity >= $this->minimumQuantity ? $this->discountPrice : $this->product->price;
        return $this->quantity * $price;
    }
}
