<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Cart;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateProductUnitsFromTest extends WebTestCase
{
    private const URI = '/cart/%s/product-seller/%s/units/s%';
    private const EXISTING_PRODUCT_ID = 'c05b821f-fee0-4e5d-90a3-d234d2e93c9b';

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
        $units = rand (1, 10);
        $this->client->request('PATCH', sprintf(self::URI,'invalid_uuid', 'invalid_uuid', $units));
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_when_cart_not_exist_it_responses_not_found()
    {
        $units = rand (1, 10);
        $this->client->request(
            'PATCH',
            sprintf(
                self::URI,
                RamseyUuid::uuid4()->toString(),
                RamseyUuid::uuid4()->toString(),
                $units
            )
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_when_product_not_exist_it_responses_not_found()
    {
        $units = rand (1, 10);
        $cartId = RamseyUuid::uuid4()->toString();
        $this->client->request(
            'POST',
            sprintf(
                '/cart/%s/product-seller/%s',
                $cartId,
                self::EXISTING_PRODUCT_ID
            )
        );
        $this->client->request(
            'PATCH',
            sprintf(
                self::URI,
                $cartId,
                RamseyUuid::uuid4()->toString(),
                $units
            )
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_when_product_exist_it_responses_ok()
    {
        $units = rand (-5, 5);
        $cartId = RamseyUuid::uuid4()->toString();
        $this->client->request(
            'POST',
            sprintf(
                '/cart/%s/product-seller/%s',
                $cartId,
                self::EXISTING_PRODUCT_ID
            )
        );
        $this->client->request(
            'PATCH',
            sprintf(
                self::URI,
                $cartId,
                self::EXISTING_PRODUCT_ID,
                $units
            )
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
