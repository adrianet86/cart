<?php

declare(strict_types = 1);

namespace App\Cart\Domain\Product;

use App\Cart\Domain\Uuid;

interface ProductSellerRepository
{
    public function create(ProductSeller $productSeller): void;

    /**
     * @throws ProductSellerNotFoundException
     */
    public function deleteById(Uuid $productSellerId): void;

    /**
     * @throws ProductSellerNotFoundException
     */
    public function find(Uuid $id): ProductSeller;
}
