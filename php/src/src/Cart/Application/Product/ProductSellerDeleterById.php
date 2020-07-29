<?php

declare(strict_types = 1);

namespace App\Cart\Application\Product;

use App\Cart\Domain\Product\ProductSellerRepository;
use App\Cart\Domain\Uuid;

class ProductSellerDeleterById
{
    private $repository;

    public function __construct(ProductSellerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ProductSellerDeleterByIdRequest $request): void
    {
        $this->repository->deleteById(new Uuid($request->id()));
    }
}
