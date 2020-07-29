<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

class AdderProductToCartRequest
{
    private $cartId;
    private $productSellerId;

    public function __construct(string $cartId, string $productSellerId)
    {
        $this->cartId = $cartId;
        $this->productSellerId = $productSellerId;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function productSellerId(): string
    {
        return $this->productSellerId;
    }
}
