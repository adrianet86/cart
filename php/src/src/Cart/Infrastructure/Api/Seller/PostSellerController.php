<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Seller;

use App\Cart\Application\Seller\SellerCreator;
use App\Cart\Application\Seller\SellerCreatorRequest;
use App\Cart\Domain\InvalidUuidException;
use App\Cart\Domain\Seller\InvalidSellerNameException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostSellerController extends AbstractController
{
    private $creator;

    public function __construct(SellerCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            ($this->creator)(
                new SellerCreatorRequest(
                    isset($data['id']) ? $data['id'] : '',
                    isset($data['name']) ? $data['name'] : ''
                )
            );
            $response = null;
            $httpCode = Response::HTTP_CREATED;
        } catch (InvalidSellerNameException | InvalidUuidException $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_BAD_REQUEST;
        } catch (\Exception $exception) {
            $response = $exception->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $httpCode);
    }
}
