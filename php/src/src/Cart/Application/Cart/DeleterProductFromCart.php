<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

use App\Cart\Domain\Cart\CartRepository;
use App\Cart\Domain\Product\ProductSellerNotFoundException;
use App\Cart\Domain\Product\ProductSellerRepository;
use App\Cart\Domain\Uuid;

class DeleterProductFromCart
{
    private $cartRepository;
    private $productSellerRepository;

    public function __construct(CartRepository $cartRepository, ProductSellerRepository $productSellerRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productSellerRepository = $productSellerRepository;
    }

    public function __invoke(DeleteProductFromCartRequest $request)
    {
        $cartId = new Uuid($request->cartId());
        $productSellerId = new Uuid($request->productSellerId());
        $cart = $this->cartRepository->find($cartId);
        $this->productSellerRepository->find($productSellerId);

        if (!$cart->removeProduct($productSellerId)) {
            throw new ProductSellerNotFoundException(
                sprintf(
                    'Product does not exist in this cart product_seller_id: %s',
                    $productSellerId->toString()
                )
            );
        }

        $this->cartRepository->save($cart);
    }
}
