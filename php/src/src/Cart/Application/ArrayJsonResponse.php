<?php

declare(strict_types = 1);

namespace App\Cart\Application;

interface ArrayJsonResponse
{
    public function toJson(): string;

    public function toArray(): array;
}
