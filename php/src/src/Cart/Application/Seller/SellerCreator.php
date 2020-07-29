<?php

declare(strict_types=1);

namespace App\Cart\Application\Seller;

use App\Cart\Domain\Seller\Seller;
use App\Cart\Domain\Seller\SellerRepository;
use App\Cart\Domain\Uuid;

class SellerCreator
{
    private $repository;

    public function __construct(SellerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SellerCreatorRequest $request): void
    {
        $this->repository->create(new Seller(new Uuid($request->id()), $request->name()));
    }
}
