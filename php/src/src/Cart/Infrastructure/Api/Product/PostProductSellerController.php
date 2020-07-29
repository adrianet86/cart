<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\Product;

use App\Cart\Application\Product\ProductSellerCreator;
use App\Cart\Application\Product\ProductSellerCreatorRequest;
use App\Cart\Domain\InvalidUuidException;
use App\Cart\Domain\Seller\SellerNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostProductSellerController extends AbstractController
{
    private $creator;

    public function __construct(ProductSellerCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(Request $request)
    {
       try {
           $data = json_decode($request->getContent(), true);
           // TODO: request parameters validation
           ($this->creator)(new ProductSellerCreatorRequest(
               isset($data['id']) ? $data['id'] : '',
               isset($data['product_id']) ? $data['product_id'] : '',
               isset($data['seller_id']) ? $data['seller_id'] : '',
               isset($data['price']) ? $data['price'] : 0
           ));
           $response = null;
           $httpCode = Response::HTTP_CREATED;
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
