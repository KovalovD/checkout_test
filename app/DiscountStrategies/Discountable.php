<?php

namespace App\DiscountStrategies;

interface Discountable
{
    public function countTotal(): float;
}
