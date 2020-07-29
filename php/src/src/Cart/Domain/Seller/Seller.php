<?php

declare(strict_types = 1);

namespace App\Cart\Domain\Seller;

use App\Cart\Domain\Uuid;

class Seller
{
    private $id;
    private $name;

    public function __construct(Uuid $id, string $name)
    {
        $this->id = $id;
        $this->setName($name);
    }

    private function setName(string $name): void
    {
        if (empty($name)) {
            throw new InvalidSellerNameException('Seller name can not be blank');
        }
        $this->name = $name;
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}
