<?php

namespace App;

use InvalidArgumentException;

class UUID
{
    public function __construct(private string $uuid)
    {
        if (!uuid_is_valid($uuid)) {
            throw new InvalidArgumentException();
        }
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public static function random()
    {
        return new self(uuid_create(UUID_TYPE_RANDOM));
    }
}
