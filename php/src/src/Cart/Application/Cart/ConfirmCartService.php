<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

use App\Cart\Domain\Cart\CartRepository;
use App\Cart\Domain\Uuid;

class ConfirmCartService
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(ConfirmCartRequest $request): void
    {
        $cart = $this->cartRepository->find(new Uuid($request->cartId()));
        $cart->confirm();
        $this->cartRepository->save($cart);
    }
}
