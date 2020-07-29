<?php

declare(strict_types=1);

namespace App\Cart\Domain\Product;

use App\Cart\Domain\Uuid;

class ProductSeller
{
    private $id;
    private $productId;
    private $sellerId;
    private $price;

    public function __construct(Uuid $id, Uuid $productId, Uuid $sellerId, int $price)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->sellerId = $sellerId;
        $this->price = $price;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function price(): int
    {
        return $this->price;
    }
}
