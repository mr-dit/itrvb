<?php

namespace App\Repositories;

use App\Interfaces\UsersRepositoryInterface;
use App\User;
use App\UUID;
use App\Exceptions\UserNotFoundException;
use Error;
use PDO;
use PDOException;

class UsersRepository implements UsersRepositoryInterface
{
  public function __construct(
    private PDO $pdo
  ) {}
  public function get(UUID $uuid): User
  {

    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE uuid = :uuid");

    try {
      $stmt->execute([
        ":uuid" => $uuid
      ]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$result) {
        throw new UserNotFoundException();
      }
    } catch (PDOException $e) {
      throw new Error("Ошибка при получении пользователя: " . $e->getMessage());
    }

    return new User(
      $result['uuid'],
      $result['username'],
      $result['nickname'],
      $result['first_name'],
      $result['last_name']
    );
  }
}
