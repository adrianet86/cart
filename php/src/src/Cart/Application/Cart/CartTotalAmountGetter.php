<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

use App\Cart\Application\ArrayJsonResponse;
use App\Cart\Domain\Cart\CartRepository;
use App\Cart\Domain\Uuid;

class CartTotalAmountGetter
{
    private $repository;

    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CartTotalAmountRequest $request): ArrayJsonResponse
    {
        $cart = $this->repository->find(new Uuid($request->cartId()));

        return new CartTotalAmountResponseArray($cart->id()->toString(), $cart->totalAmount());
    }
}
