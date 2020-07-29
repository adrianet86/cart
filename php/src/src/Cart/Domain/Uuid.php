<?php

declare(strict_types = 1);

namespace App\Cart\Domain;

class Uuid
{
    private $uuid;

    public function __construct(string $uuid)
    {
        if (!\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new InvalidUuidException(sprintf('Invalid uuid: %s', $uuid));
        }

        $this->uuid = $uuid;
    }

    public function toString(): string
    {
        return $this->uuid;
    }
}
