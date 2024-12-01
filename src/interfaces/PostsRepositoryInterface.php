<?php

namespace App;

interface PostsRepositoryInterface
{
    public function get(UUID $uuid): Post;
    public function save(Post $post): void;
}
