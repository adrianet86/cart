<?php

declare(strict_types = 1);

namespace App\Cart\Domain\Seller;

use App\Cart\Domain\Uuid;

interface SellerRepository
{
    public function create(Seller $seller): void;

    /**
     * @throws SellerNotFoundException
     */
    public function deleteById(Uuid $sellerId): void;

    /**
     * @throws SellerNotFoundException
     */
    public function findById(Uuid $sellerId): Seller;
}
