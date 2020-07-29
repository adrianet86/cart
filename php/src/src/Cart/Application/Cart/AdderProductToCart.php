<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

use App\Cart\Domain\Cart\Cart;
use App\Cart\Domain\Cart\CartRepository;
use App\Cart\Domain\Product\ProductSellerRepository;
use App\Cart\Domain\Uuid;

class AdderProductToCart
{
    private $cartRepository;
    private $productSellerRepository;

    public function __construct(CartRepository $cartRepository, ProductSellerRepository $productSellerRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productSellerRepository = $productSellerRepository;
    }

    public function __invoke(AdderProductToCartRequest $request)
    {
        $cartId = new Uuid($request->cartId());
        $productSeller = $this->productSellerRepository->find(new Uuid($request->productSellerId()));

        // TODO: move to a domain service ¿?
        $cart = $this->buildCart($cartId);
        $cart->addProduct($productSeller);

        $this->cartRepository->save($cart);
    }

    private function buildCart(Uuid $cartId): Cart
    {
        $cart = $this->cartRepository->searchById($cartId);
        if (!$cart instanceof Cart) {
            $cart = new Cart($cartId);
        }
        return $cart;
    }
}
