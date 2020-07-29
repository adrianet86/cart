<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Persistence\File\Product;

use App\Cart\Domain\Product\ProductSeller;
use App\Cart\Domain\Product\ProductSellerNotFoundException;
use App\Cart\Domain\Product\ProductSellerRepository;
use App\Cart\Domain\Uuid;

class FileProductSellerRepository implements ProductSellerRepository
{
    private $items;
    private $fileName;

    public function __construct($file = null)
    {
        if (!is_string($file) || empty($file)) {
            $file = 'FileProductSellerRepository.file_db';
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

    public function create(ProductSeller $productSeller): void
    {
        $this->items[$productSeller->id()->toString()] = $productSeller;
        $this->writeFile();
    }

    public function deleteById(Uuid $productSellerId): void
    {
        $id = $productSellerId->toString();
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
            $this->writeFile();
            return;
        }

        throw new ProductSellerNotFoundException(sprintf('Product seller not found for id %s ', $id));
    }

    private function writeFile(): void
    {
        file_put_contents($this->fileName, serialize($this->items));
    }

    public function find(Uuid $id): ProductSeller
    {
        $id = $id->toString();
        if (isset($this->items[$id])) {
            return $this->items[$id];
        }

        throw new ProductSellerNotFoundException(sprintf('Product seller not found for id %s ', $id));
    }
}
