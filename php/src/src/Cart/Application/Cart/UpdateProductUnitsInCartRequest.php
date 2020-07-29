<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

class UpdateProductUnitsInCartRequest
{
    private $cartId;
    private $productSellerId;
    private $units;

    public function __construct(string $cartId, string $productSellerId, int $units)
    {
        $this->cartId = $cartId;
        $this->productSellerId = $productSellerId;
        $this->units = $units;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function productSellerId(): string
    {
        return $this->productSellerId;
    }

    public function units(): int
    {
        return $this->units;
    }
}
