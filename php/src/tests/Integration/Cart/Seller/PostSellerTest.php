<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Seller;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostSellerTest extends WebTestCase
{
    public function test_invalid_id_response_a_bad_request()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => 'invalid_uuid',
                'name' => 'seller name'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_empty_seller_name_response_a_bad_request()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => RamseyUuid::uuid4()->toString(),
                'name' => ''
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_creates_a_seller_response_created_status()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => RamseyUuid::uuid4()->toString(),
                'name' => 'seller name'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
