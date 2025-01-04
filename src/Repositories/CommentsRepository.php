<?php

namespace App\Repositories;

use App\Interfaces\CommentsRepositoryInterface;
use App\Comment;
use App\UUID;

class CommentsRepository implements CommentsRepositoryInterface
{
  public function __construct(
    private \PDO $connection
  ) {}

  public function save(Comment $comment): void
  {
    $statement = $this->connection->prepare(
      'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
            VALUES (:uuid, :post_uuid, :author_uuid, :text)'
    );

    $statement->execute([
      ':uuid' => (string)$comment->getUuid(),
      ':post_uuid' => (string)$comment->getPostUuid(),
      ':author_uuid' => (string)$comment->getAuthorUuid(),
      ':text' => $comment->getContent(),
    ]);
  }

  public function get(Uuid $uuid): Comment
  {
    $statement = $this->connection->prepare(
      'SELECT * FROM comments WHERE uuid = :uuid'
    );

    $statement->execute([
      ':uuid' => (string)$uuid,
    ]);

    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    if ($result === false) {
      throw new \Exception("Комментарий не найден: $uuid");
    }

    return new Comment(
      new Uuid($result['uuid']),
      new Uuid($result['post_uuid']),
      new Uuid($result['author_uuid']),
      $result['text']
    );
  }
}
