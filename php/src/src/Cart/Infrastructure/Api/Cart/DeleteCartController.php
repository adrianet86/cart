<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Cart;

use App\Cart\Application\Cart\CartDeleterById;
use App\Cart\Application\Cart\CartDeleterByIdRequest;
use App\Cart\Domain\Cart\CartNotFoundException;
use App\Cart\Domain\InvalidUuidException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteCartController extends AbstractController
{
    private $deleterById;

    public function __construct(CartDeleterById $deleterById)
    {
        $this->deleterById = $deleterById;
    }

    public function __invoke(string $id): JsonResponse
    {
        try {
            ($this->deleterById)(new CartDeleterByIdRequest($id));
            $response = null;
            $httpCode = Response::HTTP_NO_CONTENT;
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
