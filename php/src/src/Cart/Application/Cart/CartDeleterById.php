<?php

declare(strict_types = 1);

namespace App\Cart\Application\Cart;

use App\Cart\Domain\Cart\CartRepository;
use App\Cart\Domain\Uuid;

class CartDeleterById
{
    private $repository;

    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CartDeleterByIdRequest $request)
    {
        $this->repository->deleteById(new Uuid($request->id()));
    }
}
