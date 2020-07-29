<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Cart;

use App\Cart\Application\Cart\DeleteProductFromCartRequest;
use App\Cart\Application\Cart\DeleterProductFromCart;
use App\Cart\Domain\Cart\CartNotFoundException;
use App\Cart\Domain\InvalidUuidException;
use App\Cart\Domain\Product\ProductSellerNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteProductFromCartController extends AbstractController
{
    private $deleter;

    public function __construct(DeleterProductFromCart $deleter)
    {
        $this->deleter = $deleter;
    }

    public function __invoke(string $id, string $productSellerId): JsonResponse
    {
        try {
            ($this->deleter)(new DeleteProductFromCartRequest($id, $productSellerId));
            $response = null;
            $httpCode = Response::HTTP_NO_CONTENT;
        } catch (InvalidUuidException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_BAD_REQUEST;
        } catch (CartNotFoundException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_NOT_FOUND;
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
