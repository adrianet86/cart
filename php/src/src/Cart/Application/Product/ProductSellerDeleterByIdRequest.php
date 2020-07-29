<?php

declare(strict_types = 1);

namespace App\Cart\Application\Product;

class ProductSellerDeleterByIdRequest
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
