<?php

namespace App\Interfaces;

use App\User;
use App\UUID;

interface UsersRepositoryInterface
{
  public function get(UUID $uuid): User;
}
