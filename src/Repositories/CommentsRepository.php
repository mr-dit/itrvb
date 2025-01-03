<?php

namespace App\Repositories;

use App\Interfaces\CommentsRepositoryInterface;
use PDO;
use App\Comment;
use App\UUID;

class CommentsRepository implements CommentsRepositoryInterface
{
  public function __construct(
    private PDO $connection
  ) {}

  public function save(Comment $comment): void
  {
    $statement = $this->connection->prepare(
      'INSERT INTO comments (uuid, postUuid, authorUuid, text)
            VALUES (:uuid, :postUuid, :authorUuid, :text)'
    );

    $statement->execute([
      ':uuid' => (string)$comment->getUuid(),
      ':postUuid' => (string)$comment->getPostUuid(),
      ':authorUuid' => (string)$comment->getAuthorUuid(),
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

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
      throw new \Exception("Комментарий не найден: $uuid");
    }

    return new Comment(
      new Uuid($result['uuid']),
      new Uuid($result['postUuid']),
      new Uuid($result['authorUuid']),
      $result['text']
    );
  }
}
