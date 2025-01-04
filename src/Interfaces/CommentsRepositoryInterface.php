<?php

namespace App\Interfaces;

use App\Comment;
use App\UUID;

interface CommentsRepositoryInterface
{
    public function get(UUID $uuid): Comment;
    public function save(Comment $comment): void;
}
