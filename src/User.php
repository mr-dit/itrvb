<?php

namespace App;

class User
{
    public function __construct(
        private UUID $uuid,
        private UUID $authorUuid,
        private string $nickname,
        private string $firstName,
        private string $lastName
    ) {
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    public function getAuthorUuid(): UUID
    {
        return $this->authorUuid;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
