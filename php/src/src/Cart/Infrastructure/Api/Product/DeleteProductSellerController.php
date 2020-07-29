<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Product;

use App\Cart\Application\Product\ProductSellerDeleterById;
use App\Cart\Application\Product\ProductSellerDeleterByIdRequest;
use App\Cart\Domain\InvalidUuidException;
use App\Cart\Domain\Product\ProductSellerNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteProductSellerController extends AbstractController
{
    private $deleterById;

    public function __construct(ProductSellerDeleterById $deleterById)
    {
        $this->deleterById = $deleterById;
    }

    public function __invoke(Request $request, string $id)
    {
       try {
           ($this->deleterById)(new ProductSellerDeleterByIdRequest($id));
           $response = null;
           $httpCode = Response::HTTP_NO_CONTENT;
       } catch (InvalidUuidException $exception) {
           $response = $exception->getMessage();
           $httpCode = Response::HTTP_BAD_REQUEST;
       } catch (ProductSellerNotFoundException $exception) {
           $response = $exception->getMessage();
           $httpCode = Response::HTTP_NOT_FOUND;
       } catch (\Exception $exception) {
           $response = $exception->getMessage();
           $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
       }

        return new JsonResponse($response, $httpCode);
    }
}
