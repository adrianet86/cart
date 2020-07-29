<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Cart;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetCartAmountTest extends WebTestCase
{
    private const URI = '/cart/%s/amount';
    private const PRODUCT_ID = '69ab7e46-c3a8-4612-aef2-7f17cb92d62e';
    private const PRODUCT_AMOUNT = 100;

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
                'id' => self::PRODUCT_ID,
                'product_id' => RamseyUuid::uuid4()->toString(),
                'seller_id' => $sellerId,
                'price' => self::PRODUCT_AMOUNT
            ])
        );
    }

    public function test_when_id_is_not_valid_uuid_it_responses_bad_request()
    {
        $this->client->request('GET', sprintf(self::URI,'invalid_uuid'));
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_when_cart_not_exist_it_responses_not_found()
    {
        $this->client->request('GET', sprintf(self::URI, RamseyUuid::uuid4()->toString()));
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_when_response_is_ok_it_has_expected_json_structure()
    {
        $cartId = RamseyUuid::uuid4()->toString();
        $this->client->request(
            'POST',
            sprintf('/cart/%s/product-seller/%s', $cartId,self::PRODUCT_ID)
        );
        $this->client->request('GET', sprintf(self::URI, $cartId));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('total_amount', $response);
    }
}
