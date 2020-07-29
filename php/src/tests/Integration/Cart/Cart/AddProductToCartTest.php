<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Cart;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AddProductToCartTest extends WebTestCase
{
    private const EXISTING_PRODUCT_ID = '08113393-aece-4558-a24f-96ffbe4fa0b4';
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $sellerId = RamseyUuid::uuid4()->toString();
        $this->client->request(
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
        $this->client->request(
            'POST',
            '/product-seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => self::EXISTING_PRODUCT_ID,
                'product_id' => RamseyUuid::uuid4()->toString(),
                'seller_id' => $sellerId,
                'price' => 1000
            ])
        );
    }

    public function test_when_id_is_not_valid_uuid_it_responses_bad_request()
    {
        $this->client->request(
            'POST',
            sprintf('/cart/invalid_uuid/product-seller/invalid_uuid')
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_when_new_cart_is_created_it_responses_created_code()
    {
        $this->client->request(
            'POST',
            sprintf(
                '/cart/%s/product-seller/%s',
                RamseyUuid::uuid4()->toString(),
                self::EXISTING_PRODUCT_ID
            )
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function test_when_product_is_added_to_existing_cart_it_responses_created_code()
    {
        $cartId = RamseyUuid::uuid4()->toString();
        $this->client->request(
            'POST',
            sprintf('/cart/%s/product-seller/%s', $cartId, self::EXISTING_PRODUCT_ID)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->client->request(
            'POST',
            sprintf('/cart/%s/product-seller/%s', $cartId, self::EXISTING_PRODUCT_ID)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
