<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Cart;

use App\Cart\Application\Cart\UpdateProductUnitsInCartRequest;
use App\Cart\Application\Cart\UpdaterProductUnitsInCart;
use App\Cart\Domain\Cart\CartNotFoundException;
use App\Cart\Domain\InvalidUuidException;
use App\Cart\Domain\Product\ProductSellerNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PatchProductUnitsToCartController extends AbstractController
{
    private $updater;

    public function __construct(UpdaterProductUnitsInCart $updater)
    {
        $this->updater = $updater;
    }

    public function __invoke(string $id, string $productSellerId, string $units): JsonResponse
    {
        try {
            $response = (($this->updater)(new UpdateProductUnitsInCartRequest($id, $productSellerId, (int)$units)));
            $httpCode = Response::HTTP_OK;
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
