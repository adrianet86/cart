<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Cart\Domain\Cart;

use App\Cart\Domain\Cart\Cart;
use App\Cart\Domain\Product\ProductSeller;
use App\Cart\Domain\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class CartTest extends TestCase
{
    public function test_add_one_product_works()
    {
        $product = $this->createMock(ProductSeller::class);
        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));

        $cart->addProduct($product);

        $this->assertEquals(1, $cart->totalProducts());
    }

    public function test_add_same_product_increases_units()
    {
        $productSeller = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        );

        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));
        $totalUnits = rand(3, 10);

        for ($i = 1; $i <= $totalUnits; $i++) {
            $cart->addProduct($productSeller);
        }
        $productItem = $cart->getProductById($productSeller->id());

        $this->assertEquals(1, $cart->totalProducts());
        $this->assertEquals($totalUnits, $productItem->units());
    }

    public function test_add_many_different_products_works()
    {
        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));
        $cart->addProduct(new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        ));
        $cart->addProduct(new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        ));

        $this->assertEquals(2, $cart->totalProducts());
    }

    public function test_calculate_total_amount_works()
    {
        $price1 = rand(100, 1000);
        $price2 = rand(100, 1000);
        $productSeller1 = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            $price1
        );
        $productSeller2 = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            $price2
        );

        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));

        $totalUnitsProduct1 = rand(3, 10);
        $totalUnitsProduct2 = rand(3, 10);

        for ($i = 1; $i <= $totalUnitsProduct1; $i++) {
            $cart->addProduct($productSeller1);
        }
        for ($i = 1; $i <= $totalUnitsProduct2; $i++) {
            $cart->addProduct($productSeller2);
        }

        $expectedTotalAmount = ($price1 * $totalUnitsProduct1) + ($price2 * $totalUnitsProduct2);
        $this->assertEquals($expectedTotalAmount, $cart->totalAmount());
    }

    public function test_when_a_units_are_updated_with_0_then_product_is_removed(): void
    {
        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));
        $productSeller = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        );
        $cart->addProduct($productSeller);
        $cart->updateProductUnits($productSeller, 0);
        $this->assertEmpty($cart->totalProducts());
    }

    public function test_when_positive_units_are_updated_then_units_are_added(): void
    {
        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));
        $productSeller = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        );
        $units = rand(1, 10);
        $cart->addProduct($productSeller);
        $cart->updateProductUnits($productSeller, $units);
        $this->assertEquals($units + 1, $cart->getProductById($productSeller->id())->units());
    }

    public function test_when_product_not_exists_then_is_created_with_units(): void
    {
        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));
        $productSeller = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        );
        $unitsAdded = 5;
        $cart->updateProductUnits($productSeller, $unitsAdded);

        $this->assertEquals($unitsAdded, $cart->getProductById($productSeller->id())->units());
    }

    public function test_when_negative_units_are_updated_then_units_are_deducted(): void
    {
        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));
        $productSeller = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        );
        $unitsAdded = 5;
        $unitsDeducted = -3;
        $expectedUnits = $unitsAdded - ($unitsDeducted * -1);
        $cart->updateProductUnits($productSeller, $unitsAdded);
        $cart->updateProductUnits($productSeller, $unitsDeducted);

        $this->assertEquals($expectedUnits, $cart->getProductById($productSeller->id())->units());
    }

    public function test_when_total_units_are_negative_then_product_is_removed(): void
    {
        $cart = new Cart(new Uuid(RamseyUuid::uuid4()->toString()));
        $productSeller = new ProductSeller(
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            new Uuid(RamseyUuid::uuid4()->toString()),
            100
        );
        $unitsDeducted = -3;
        $cart->addProduct($productSeller);
        $cart->updateProductUnits($productSeller, $unitsDeducted);

        $this->assertEquals(0, $cart->totalProducts());
    }
}
