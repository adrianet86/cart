<?php

declare(strict_types = 1);

namespace App\Cart\Application\Seller;

class SellerDeleterByIdRequest
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
