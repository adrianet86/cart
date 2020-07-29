<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

use App\Cart\Domain\Cart\CartRepository;
use App\Cart\Domain\Product\ProductSellerRepository;
use App\Cart\Domain\Uuid;

class UpdaterProductUnitsInCart
{
    private $cartRepository;
    private $productSellerRepository;

    public function __construct(CartRepository $cartRepository, ProductSellerRepository $productSellerRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productSellerRepository = $productSellerRepository;
    }

    public function __invoke(UpdateProductUnitsInCartRequest $request): void
    {
        $cart = $this->cartRepository->find(new Uuid($request->cartId()));
        $productSeller = $this->productSellerRepository->find(new Uuid($request->productSellerId()));

        $cart->updateProductUnits($productSeller, $request->units());

        $this->cartRepository->save($cart);
    }
}
