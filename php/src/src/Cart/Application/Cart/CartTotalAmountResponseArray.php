<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

use App\Cart\Application\ArrayJsonResponse;

class CartTotalAmountResponseArray implements ArrayJsonResponse
{
    private $cartId;
    private $totalAmount;

    public function __construct(string $cartId, int $totalAmount)
    {
        $this->cartId = $cartId;
        $this->totalAmount = $totalAmount;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'cart_id' => $this->cartId,
            'total_amount' => $this->totalAmount
        ];
    }
}
