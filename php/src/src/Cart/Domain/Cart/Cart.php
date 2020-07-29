<?php

declare(strict_types = 1);

namespace App\Cart\Domain\Cart;

use App\Cart\Domain\Product\ProductSeller;
use App\Cart\Domain\Uuid;

class Cart
{
    private $id;
    private $items;
    private $isConfirmed;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
        $this->items = [];
        $this->isConfirmed = false;
    }

    private function idToKey(Uuid $id): string
    {
        return md5($id->toString());
    }

    public function addProduct(ProductSeller $productSeller): void
    {
        $this->updateProductUnits($productSeller, 1);
    }

    public function updateProductUnits(ProductSeller $productSeller, int $units): void
    {
        $productSellerId = $productSeller->id();
        if ($units === 0) {
            $this->removeProduct($productSellerId);
            return;
        }
        if (!$this->existsProduct($productSellerId)) {
            $this->items[$this->idToKey($productSellerId)] = ProductItem::createWithUnits($productSeller, $units);
        } else {
            $this->getProductById($productSellerId)->addUnits($units);
            if ($this->getProductById($productSellerId)->units() <= 0) {
                $this->removeProduct($productSellerId);
                return;
            }
        }
    }

    private function existsProduct(Uuid $productSellerId): bool
    {
        return array_key_exists($this->idToKey($productSellerId), $this->items);
    }

    public function removeProduct(Uuid $productSellerId): bool
    {
        if ($this->existsProduct($productSellerId)) {
            unset($this->items[$this->idToKey($productSellerId)]);
            return true;
        }

        return false;
    }

    public function totalAmount(): int
    {
        if (empty($this->items)) {
            return 0;
        }

        $amount = 0;
        foreach ($this->items as $item) {
            $amount += $item->totalAmount();
        }
        return $amount;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function confirm(): void
    {
        if ($this->isEmpty()) {
            throw new CartEmptyException('Can not confirm an empty cart');
        }
        $this->isConfirmed = true;
    }

    private function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function totalProducts(): int
    {
        return count($this->items);
    }

    public function getProductById(Uuid $id): ?ProductItem
    {
        if ($this->existsProduct($id)) {
            return $this->items[$this->idToKey($id)];
        }

        return null;
    }
}

