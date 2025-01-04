<?php

namespace App\Interfaces;

use App\Post;
use App\UUID;

interface PostsRepositoryInterface
{
    public function get(UUID $uuid): Post;
    public function save(Post $post): void;
}
