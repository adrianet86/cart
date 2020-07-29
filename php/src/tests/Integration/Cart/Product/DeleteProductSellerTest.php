<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Product;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteProductSellerTest extends WebTestCase
{
    public function test_when_a_product_seller_is_deleted_it_responses_no_content_code()
    {
        $client = static::createClient();
        $sellerId = RamseyUuid::uuid4()->toString();
        $productSellerId = RamseyUuid::uuid4()->toString();
        $client->request(
            'POST',
            '/seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['id' => $sellerId, 'name' => 'seller name'])
        );
        $client->request(
            'POST',
            '/product-seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => $productSellerId,
                'product_id' => RamseyUuid::uuid4()->toString(),
                'seller_id' => $sellerId,
                'price' => 1000
            ])
        );
        $client->request('DELETE', '/product-seller/' . $productSellerId);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function test_when_product_seller_not_exists_it_responses_not_found()
    {
        $client = static::createClient();
        $client->request('DELETE', '/product-seller/' . RamseyUuid::uuid4()->toString());

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_when_id_is_not_valid_uuid_it_responses_bad_request()
    {
        $client = static::createClient();
        $client->request('DELETE', '/product-seller/invalid_uuid');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
