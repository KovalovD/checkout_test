<?php

namespace Tests\Unit;

use App\Checkout;
use App\Enums\DiscountType;
use App\Models\DiscountRule;
use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    private Checkout $checkout;

    protected function setUp(): void
    {
        $discountRules = [
            'FR1' => new DiscountRule(DiscountType::BOGO),
            'SR1' => new DiscountRule(DiscountType::BULK, 3, 4.50),
        ];

        $this->checkout = new Checkout($discountRules);
    }

    private function products(string $id): Product
    {
        $products = [
            'FR1' => new Product('FR1', 'Fruit Tea', 3.11),
            'SR1' => new Product('SR1', 'Strawberries', 5.00),
            'CF1' => new Product('CF1', 'Coffee', 11.23),
        ];

        return $products[$id];
    }

    public function testTotalWithNoItems(): void
    {
        $this->assertEquals(0, $this->checkout->total);
    }

    public function testSingleItem(): void
    {
        $this->checkout->scan($this->products('FR1'));
        $this->assertEquals(3.11, $this->checkout->total);
    }

    public function testBOGODiscount(): void
    {
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('FR1'));
        $this->assertEquals(3.11, $this->checkout->total);
    }

    public function testBOGOPlusOneDiscount(): void
    {
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('FR1'));
        $this->assertEquals(6.22, $this->checkout->total);
    }

    public function testBulkDiscount(): void
    {
        $this->checkout->scan($this->products('SR1'));
        $this->checkout->scan($this->products('SR1'));
        $this->checkout->scan($this->products('SR1'));
        $this->assertEquals(4.50 * 3, $this->checkout->total);
    }

    public function testMixedItems(): void
    {
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('SR1'));
        $this->checkout->scan($this->products('CF1'));
        // Calculate expected total with no discounts
        $expectedTotal = 3.11 + 5.00 + 11.23;
        $this->assertEquals($expectedTotal, $this->checkout->total);
    }

    public function testFirstExample(): void
    {
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('SR1'));
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('CF1'));
        $this->assertEquals(22.45, $this->checkout->total);

    }

    public function testSecondExample(): void
    {
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('FR1'));
        $this->assertEquals(3.11, $this->checkout->total);

    }

    public function testThirdExample(): void
    {
        $this->checkout->scan($this->products('SR1'));
        $this->checkout->scan($this->products('SR1'));
        $this->checkout->scan($this->products('FR1'));
        $this->checkout->scan($this->products('SR1'));
        $this->assertEquals(16.61, $this->checkout->total);

    }
}
