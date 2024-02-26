<?php

namespace App\Models;

class Product
{
    public function __construct(
        public string $id,
        public string $name,
        public float $price
    ) {
    }
}
