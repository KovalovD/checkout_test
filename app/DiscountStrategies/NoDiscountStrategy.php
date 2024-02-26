<?php

namespace App\DiscountStrategies;

use App\Models\Product;

readonly class NoDiscountStrategy implements Discountable
{

    public function __construct(private Product $product, private float $quantity)
    {
    }

    public function countTotal(): float
    {
        return $this->quantity * $this->product->price;
    }
}
