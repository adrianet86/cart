<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Cart;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteProductFromCartTest extends WebTestCase
{
    private const EXISTING_PRODUCT_ID = '244df471-7732-463f-9cce-c03a2e6abbfe';
    private const URI = '/cart/%s/product-seller/%s';

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

    public function test_when_cart_not_exists_it_responses_not_found_code()
    {
        $this->client->request(
            'DELETE',
            sprintf(
                self::URI,
                    RamseyUuid::uuid4()->toString(),
                    RamseyUuid::uuid4()->toString()
            )
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_when_id_is_not_valid_uuid_it_responses_bad_request()
    {
        $this->client->request(
            'DELETE',
            sprintf(
                self::URI,
                'invalid_uuid',
                'invalid_uuid'
            )
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_when_product_is_deleted_it_responses_no_content_code()
    {
        $cartId = RamseyUuid::uuid4()->toString();

        $this->client->request(
            'POST',
            sprintf(
                self::URI,
                $cartId,
                self::EXISTING_PRODUCT_ID
            )
        );
        $this->client->request(
            'DELETE',
            sprintf(
                self::URI,
                $cartId,
                self::EXISTING_PRODUCT_ID
            )
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function test_when_product_not_exists_in_cart_it_responses_not_found_code()
    {
        $cartId = RamseyUuid::uuid4()->toString();

        $this->client->request('POST', sprintf(self::URI, $cartId, self::EXISTING_PRODUCT_ID));
        $this->client->request('DELETE', sprintf(self::URI, $cartId, self::EXISTING_PRODUCT_ID));
        $this->client->request('DELETE', sprintf(self::URI, $cartId, self::EXISTING_PRODUCT_ID));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
