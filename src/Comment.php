<?php

namespace App;

class Comment
{
    public function __construct(
        private UUID $uuid,
        private UUID $postUuid,
        private UUID $authorUuid,
        private string $text
    ) {
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    public function getPostUuid(): UUID
    {
        return $this->postUuid;
    }

    public function getAuthorUuid(): UUID
    {
        return $this->authorUuid;
    }

    public function getContent(): string
    {
        return $this->text;
    }
}
