<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Seller;

use App\Cart\Application\Seller\SellerDeleterById;
use App\Cart\Application\Seller\SellerDeleterByIdRequest;
use App\Cart\Domain\InvalidUuidException;
use App\Cart\Domain\Seller\SellerNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteSellerController extends AbstractController
{
    private $deleterById;

    public function __construct(SellerDeleterById $deleterById)
    {
        $this->deleterById = $deleterById;
    }

    public function __invoke(Request $request, string $id)
    {
        try {
            ($this->deleterById)(new SellerDeleterByIdRequest($id));
            $response = null;
            $httpCode = Response::HTTP_NO_CONTENT;
        } catch (InvalidUuidException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_BAD_REQUEST;
        } catch (SellerNotFoundException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_NOT_FOUND;
        } catch (\Exception $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $httpCode);
    }
}
