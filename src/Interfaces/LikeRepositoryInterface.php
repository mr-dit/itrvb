<?php

namespace App\Interfaces;

use App\Like;
use App\UUID;

interface LikeRepositoryInterface
{
  public function save(Like $like): void;
  public function getByPostUuid(UUID $postUuid): array;
}
