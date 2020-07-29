<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Cart;

use App\Cart\Application\Cart\ConfirmCartRequest;
use App\Cart\Application\Cart\ConfirmCartService;
use App\Cart\Domain\Cart\CartEmptyException;
use App\Cart\Domain\Cart\CartNotFoundException;
use App\Cart\Domain\InvalidUuidException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PatchCartConfirmController extends AbstractController
{
    private $confirmCartService;

    public function __construct(ConfirmCartService $confirmCartService)
    {
        $this->confirmCartService = $confirmCartService;
    }

    public function __invoke(string $id): JsonResponse
    {
        try {
            ($this->confirmCartService)(new ConfirmCartRequest($id));
            $response = null;
            $httpCode = Response::HTTP_OK;
        } catch (InvalidUuidException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_BAD_REQUEST;
        } catch (CartNotFoundException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_NOT_FOUND;
        } catch (CartEmptyException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_NOT_ACCEPTABLE;
        } catch (\Exception $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $httpCode);
    }
}
