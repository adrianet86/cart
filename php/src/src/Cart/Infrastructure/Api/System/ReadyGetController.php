<?php

declare(strict_types = 1);

namespace App\Cart\Infrastructure\Api\System;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReadyGetController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse('ok');
    }
}
