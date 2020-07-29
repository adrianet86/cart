<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Persistence\File\Cart;

use App\Cart\Domain\Cart\Cart;
use App\Cart\Domain\Cart\CartNotFoundException;
use App\Cart\Domain\Cart\CartRepository;
use App\Cart\Domain\Uuid;

class FileCartRepository implements CartRepository
{
    private $items;
    private $fileName;

    public function __construct($file = null)
    {
        if (!is_string($file) || empty($file)) {
            $file = 'FileCartRepository.file_db';
        } else {
            $file = $file . '.file_db';
        }

        $this->fileName = realpath(__DIR__) . '/../../../../../../var/file_repositories/' . $file;

        if (!file_exists($this->fileName)) {
            file_put_contents($this->fileName, null);
        } else {
            $content = file_get_contents($this->fileName);
            if ($content) {
                $this->items = unserialize($content);
            }
        }
    }
    public function save(Cart $cart): void
    {
        $this->items[$cart->id()->toString()] = $cart;
        $this->writeFile();
    }

    public function deleteById(Uuid $cartId): void
    {
        $id = $cartId->toString();
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
            $this->writeFile();
            return;
        }

        throw new CartNotFoundException(sprintf('Cart not found for id %s ', $id));
    }

    private function writeFile(): void
    {
        file_put_contents($this->fileName, serialize($this->items));
    }

    public function searchById(Uuid $cartId): ?Cart
    {
        $id = $cartId->toString();
        if (isset($this->items[$id])) {
            return $this->items[$id];
        }
        return null;
    }

    public function find(Uuid $cartId): Cart
    {
        $cart = $this->searchById($cartId);
        if ($cart instanceof Cart) {
            return $cart;
        }
        throw new CartNotFoundException(sprintf('Cart not found for id: %s', $cartId->toString()));
    }
}
