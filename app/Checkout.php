<?php

namespace App;

use App\Enums\DiscountType;
use App\Models\DiscountRule;
use App\Models\Product;

class Checkout
{
    public float $total = 0;
    private array $cart = [];
    private array $products = [];

    public function __construct(
        private readonly array $discountRules
    ) {
    }

    public function scan(Product $item): void
    {
        if (!isset($this->cart[$item->id])) {
            $this->cart[$item->id] = 0;
        }
        $this->cart[$item->id]++;

        $this->pushProduct($item);
        $this->countTotal();
    }

    private function pushProduct(Product $product): void
    {
        $this->products[$product->id] = $product;
    }

    private function countTotal(): void
    {
        $this->total = 0;
        foreach ($this->cart as $productId => $quantity) {
            $this->total += $this->applyDiscounts($productId, $quantity);
        }
    }

    private function applyDiscounts(string $productId, float $quantity): float
    {
        /** @var DiscountRule $rule */
        $rule = $this->discountRules[$productId] ?? new DiscountRule(DiscountType::NO_DISCOUNT);
        return DiscountType::countTotal($rule, $quantity, $this->products[$productId]);
    }
}
