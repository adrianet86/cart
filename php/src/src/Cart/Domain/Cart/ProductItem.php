<?php

declare(strict_types = 1);

namespace App\Cart\Domain\Cart;

use App\Cart\Domain\Product\ProductSeller;

class ProductItem
{
    private const ONE_UNIT = 1;

    private $productSeller;
    private $units;

    private function __construct(ProductSeller $productSeller, int $units)
    {
        if ($units <= 0) {
            throw new InvalidUnitsException(sprintf('Units must be bigger than 0'));
        }
        $this->productSeller = $productSeller;
        $this->units = $units;
    }

    public static function create(ProductSeller $productSeller): self
    {
        return new self($productSeller, self::ONE_UNIT);
    }

    public static function createWithUnits(ProductSeller $productSeller, int $units): self
    {
        return new self($productSeller, $units);
    }

    public function productSeller(): ProductSeller
    {
        return $this->productSeller;
    }

    public function units(): int
    {
        return $this->units;
    }

    public function addUnits(int $units): void
    {
        $this->units += $units;
    }

    public function totalAmount(): int
    {
        return $this->units() * $this->productSeller()->price();
    }
}
