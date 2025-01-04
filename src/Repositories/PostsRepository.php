<?php

namespace App\Repositories;

use App\Interfaces\PostsRepositoryInterface;
use PDO;
use App\Post;
use App\UUID;

class PostsRepository implements PostsRepositoryInterface
{
  public function __construct(
    private PDO $connection
  ) {}

  public function save(Post $post): void
  {
    $statement = $this->connection->prepare(
      'INSERT INTO posts (uuid, author_uuid, title, text)
            VALUES (:uuid, :author_uuid, :title, :text)'
    );

    $statement->execute([
      ':uuid' => (string)$post->getUuid(),
      ':author_uuid' => (string)$post->getAuthorUuid(),
      ':title' => $post->getTitle(),
      ':text' => $post->getContent(),
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
      throw new \Exception("Пост не найден: $uuid");
    }

    return new Post(
      new Uuid($result['uuid']),
      new Uuid($result['author_uuid']),
      $result['title'],
      $result['text']
    );
  }
}
