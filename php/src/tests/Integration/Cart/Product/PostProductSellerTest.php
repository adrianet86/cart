<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Product;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostProductSellerTest extends WebTestCase
{
    public function test_when_a_product_seller_is_created_it_responses_created_code()
    {
        $client = static::createClient();
        $sellerId = RamseyUuid::uuid4()->toString();
        $client->request(
            'POST',
            '/seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => $sellerId,
                'name' => 'seller name'
            ])
        );

        $client->request(
            'POST',
            '/product-seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => RamseyUuid::uuid4()->toString(),
                'product_id' => RamseyUuid::uuid4()->toString(),
                'seller_id' => $sellerId,
                'price' => 1000
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function test_when_seller_not_exists_it_responses_not_found()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/product-seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => RamseyUuid::uuid4()->toString(),
                'product_id' => RamseyUuid::uuid4()->toString(),
                'seller_id' => RamseyUuid::uuid4()->toString(),
                'price' => 1000
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_when_id_is_not_valid_uuid_it_responses_bad_request()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/product-seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => 'invalid',
                'product_id' => RamseyUuid::uuid4()->toString(),
                'seller_id' => RamseyUuid::uuid4()->toString(),
                'price' => 1000
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
