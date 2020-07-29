<?php

declare(strict_types = 1);

namespace App\Cart\Application\Seller;

use App\Cart\Domain\Seller\SellerRepository;
use App\Cart\Domain\Uuid;

class SellerDeleterById
{
    private $repository;

    public function __construct(SellerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SellerDeleterByIdRequest $request): void
    {
        $this->repository->deleteById(new Uuid($request->id()));
    }
}
