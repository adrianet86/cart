<?php

declare(strict_types=1);

namespace App\Cart\Application\Product;

class ProductSellerCreatorRequest
{
    private $id;
    private $productId;
    private $sellerId;
    private $price;

    public function __construct(string $id, string $productId, string $sellerId, int $price)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->sellerId = $sellerId;
        $this->price = $price;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function sellerId(): string
    {
        return $this->sellerId;
    }

    public function price(): int
    {
        return $this->price;
    }
}
