<?php

declare(strict_types = 1);

namespace App\Cart\Application\Product;

use App\Cart\Domain\Uuid;
use App\Cart\Domain\Product\ProductSellerCreator as DomainProductSellerCreator;

class ProductSellerCreator
{
    private $creator;

    public function __construct(DomainProductSellerCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(ProductSellerCreatorRequest $request)
    {
        ($this->creator)(
            new Uuid($request->id()),
            new Uuid($request->productId()),
            new Uuid($request->sellerId()),
            $request->price()
        );
    }
}
