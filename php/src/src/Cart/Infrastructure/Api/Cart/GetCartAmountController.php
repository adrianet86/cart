<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Cart;

use App\Cart\Application\Cart\CartTotalAmountGetter;
use App\Cart\Application\Cart\CartTotalAmountRequest;
use App\Cart\Domain\Cart\CartNotFoundException;
use App\Cart\Domain\InvalidUuidException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCartAmountController extends AbstractController
{
    private $getter;

    public function __construct(CartTotalAmountGetter $getter)
    {
        $this->getter = $getter;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        try {
            $response = (($this->getter)(new CartTotalAmountRequest($id)))->toArray();
            $httpCode = Response::HTTP_OK;
        } catch (InvalidUuidException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_BAD_REQUEST;
        } catch (CartNotFoundException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_NOT_FOUND;
        } catch (\Exception $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $httpCode);
    }
}
