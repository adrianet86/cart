<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Cart;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConfirmCartTest extends WebTestCase
{
    private const EXISTING_PRODUCT_ID = '08113393-aece-4558-a24f-96ffbe4fa0b4';
    private const NON_EXISTING_CART_ID = '02ba2a42-2bd8-4271-998f-82fc6275bceb';
    private const FILLED_CART_ID = '35ac469f-a0c6-4a87-aa92-4d533adb2526';
    private const EMPTY_CART_ID = '55b72c7a-a5e8-4204-aaf5-b9c46a8c4ed3';

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
        $this->client->request(
            'POST',
            sprintf('/cart/%s/product-seller/%s', self::FILLED_CART_ID, self::EXISTING_PRODUCT_ID)
        );
        $this->client->request(
            'POST',
            sprintf('/cart/%s/product-seller/%s',self::EMPTY_CART_ID,self::EXISTING_PRODUCT_ID)
        );
        $this->client->request(
            'DELETE',
            sprintf('/cart/%s/product-seller/%s', self::EMPTY_CART_ID, self::EXISTING_PRODUCT_ID)
        );
    }

    public function test_when_id_is_not_valid_uuid_it_responses_bad_request()
    {
        $this->client->request(
            'PATCH',
            sprintf('/cart/invalid_uuid/confirm')
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_when_cart_not_exists_it_responses_not_found()
    {
        $this->client->request(
            'PATCH',
            sprintf('/cart/%s/confirm', self::NON_EXISTING_CART_ID)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_when_cart_is_empty_it_responses_not_acceptable()
    {
        $this->client->request(
            'PATCH',
            sprintf('/cart/%s/confirm', self::EMPTY_CART_ID)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_ACCEPTABLE);
    }

    public function test_when_a_filled_cart_is_confirmed_it_responses_ok()
    {
        $this->client->request(
            'PATCH',
            sprintf('/cart/%s/confirm', self::FILLED_CART_ID)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
