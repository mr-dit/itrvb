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
      'INSERT INTO posts (uuid, authorUuid, title, text)
            VALUES (:uuid, :authorUuid, :title, :text)'
    );

    $statement->execute([
      ':uuid' => (string)$post->getUuid(),
      ':authorUuid' => (string)$post->getAuthorUuid(),
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
      new Uuid($result['authorUuid']),
      $result['title'],
      $result['text']
    );
  }
}
