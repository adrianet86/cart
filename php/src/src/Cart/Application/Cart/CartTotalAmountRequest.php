<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

class CartTotalAmountRequest
{
    private $cartId;

    public function __construct(string $cartId)
    {
        $this->cartId = $cartId;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }
}
