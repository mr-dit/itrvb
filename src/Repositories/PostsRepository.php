<?php

namespace App\Repositories;

use App\Interfaces\PostsRepositoryInterface;
use PDO;
use App\Post;
use App\UUID;
use App\Exceptions\PostNotFoundException;
use Psr\Log\LoggerInterface;

class PostsRepository implements PostsRepositoryInterface
{
  public function __construct(
    private PDO $connection,
    private LoggerInterface $logger
  ) {}

  public function save(Post $post): void
  {
    $this->logger->info("Saving post: {$post->getUuid()}");
    $statement = $this->connection->prepare(
      'INSERT INTO posts (uuid, author_uuid, title, text)
            VALUES (:uuid, :author_uuid, :title, :text)'
    );

    $statement->execute([
      ':uuid' => (string)$post->getUuid(),
      ':author_uuid' => (string)$post->getAuthorUuid(),
      ':title' => $post->getTitle(),
      ':text' => $post->getText(),
    ]);
  }

  public function get(Uuid $uuid): Post
  {
    $statement = $this->connection->prepare(
      'SELECT * FROM posts WHERE uuid = :uuid'
    );

    $statement->execute([
      ':uuid' => (string)$uuid,
    ]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
      $this->logger->warning("Post not found: $uuid");
      throw new PostNotFoundException("Пост не найден: $uuid");
    }

    return new Post(
      new Uuid($result['uuid']),
      new Uuid($result['author_uuid']),
      $result['title'],
      $result['text']
    );
  }

  public function delete(UUID $uuid): void
  {
    $statement = $this->connection->prepare(
      'DELETE FROM posts WHERE uuid = :uuid'
    );

    $statement->execute([
      ':uuid' => (string)$uuid,
    ]);

    if ($statement->rowCount() === 0) {
      $this->logger->warning("Post not found while deleting: $uuid");
      throw new PostNotFoundException("Пост не найден: $uuid");
    }
  }
}
