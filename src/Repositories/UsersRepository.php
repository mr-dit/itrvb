<?php

namespace App\Repositories;

use App\Interfaces\UsersRepositoryInterface;
use App\User;
use App\UUID;
use App\Exceptions\UserNotFoundException;
use Error;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

class UsersRepository implements UsersRepositoryInterface
{
  public function __construct(
    private PDO $pdo,
    private LoggerInterface $logger
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
        $this->logger->warning("User not found: $uuid");
        throw new UserNotFoundException();
      }
    } catch (PDOException $e) {
      $this->logger->warning("PDOException: $uuid");
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
