<?php

declare(strict_types = 1);

namespace App\Cart\Domain\Product;

use App\Cart\Domain\Seller\SellerRepository;
use App\Cart\Domain\Uuid;

class ProductSellerCreator
{
    private $sellerRepository;
    private $productSellerRepository;

    public function __construct(SellerRepository $sellerRepository, ProductSellerRepository $productSellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
        $this->productSellerRepository = $productSellerRepository;
    }

    public function __invoke(Uuid $productSellerId, Uuid $productId, Uuid $sellerId, int $price): ProductSeller
    {
        // TODO: validate product exists
        // TODO: validate is a new product for this seller

        $this->sellerRepository->findById($sellerId);

        $productSeller = new ProductSeller($productSellerId, $productId, $sellerId, $price);
        $this->productSellerRepository->create($productSeller);

        return $productSeller;
    }
}
