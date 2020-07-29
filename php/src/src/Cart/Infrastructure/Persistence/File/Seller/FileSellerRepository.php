<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Persistence\File\Seller;

use App\Cart\Domain\Seller\Seller;
use App\Cart\Domain\Seller\SellerNotFoundException;
use App\Cart\Domain\Seller\SellerRepository;
use App\Cart\Domain\Uuid;

class FileSellerRepository implements SellerRepository
{
    private $items;
    private $fileName;

    public function __construct($file = null)
    {
        if (!is_string($file) || empty($file)) {
            $file = 'FileSellerRepository.file_db';
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

    public function create(Seller $seller): void
    {
        $this->items[$seller->id()->toString()] = $seller;
        $this->writeFile();
    }

    public function deleteById(Uuid $sellerId): void
    {
        $id = $sellerId->toString();
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
            $this->writeFile();
            return;
        }

        throw new SellerNotFoundException(sprintf('Seller not found for id %s', $id));
    }

    public function findById(Uuid $sellerId): Seller
    {
        $id = $sellerId->toString();
        if (isset($this->items[$id])) {
            return $this->items[$id];
        }

        throw new SellerNotFoundException(sprintf('Seller not found for id %s', $id));
    }

    private function writeFile(): void
    {
        file_put_contents($this->fileName, serialize($this->items));
    }
}
