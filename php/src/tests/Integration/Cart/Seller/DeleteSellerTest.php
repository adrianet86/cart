<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Cart\Seller;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteSellerTest extends WebTestCase
{
    public function test_invalid_id_response_a_bad_request()
    {
        $client = static::createClient();

        $client->request('DELETE', '/seller/invalid_uuid');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function test_id_non_existing_response_a_not_found()
    {
        $client = static::createClient();

        $client->request('DELETE','/seller/f0702df6-47bf-4181-8e20-f3d8e177c954');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function test_it_deletes_a_seller_response_no_content()
    {
        $client = static::createClient();
        $id = RamseyUuid::uuid4()->toString();
        $client->request(
            'POST',
            '/seller',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => $id,
                'name' => 'seller name'
            ])
        );

        $client->request('DELETE', '/seller/' . $id);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
