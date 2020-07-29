<?php

declare(strict_types = 1);

namespace App\Cart\Domain\Cart;

use App\Cart\Domain\Uuid;

interface CartRepository
{
    public function save(Cart $cart): void;

    /**
     * @throws CartNotFoundException
     */
    public function deleteById(Uuid $cartId): void;

    public function searchById(Uuid $cartId): ?Cart;

    /**
     * @throws CartNotFoundException
     */
    public function find(Uuid $cartId): Cart;
}
