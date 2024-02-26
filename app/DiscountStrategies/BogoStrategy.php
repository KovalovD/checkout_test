<?php

namespace App\DiscountStrategies;

use App\Models\Product;

readonly class BogoStrategy implements Discountable
{

    public function __construct(private Product $product, private float $quantity)
    {
    }

    public function countTotal(): float
    {
        return ceil($this->quantity / 2) * $this->product->price;
    }
}
