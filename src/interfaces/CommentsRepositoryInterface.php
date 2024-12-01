<?php

namespace App;

interface CommentsRepositoryInterface
{
    public function get(UUID $uuid): Comment;
    public function save(Comment $comment): void;
}
