<?php

namespace App\Tests;

use App\Comment;
use App\Repositories\CommentsRepository;
use PHPUnit\Framework\TestCase;
use PDO;
use App\UUID;


class CommentsRepositoryTest extends TestCase
{
  private PDO $pdo;
  private CommentsRepository $repository;

  protected function setUp(): void
  {
    $this->pdo = new PDO('sqlite:' . __DIR__ . '/../../database.sqlite');
    $this->pdo->exec('DELETE FROM comments');
    $this->repository = new CommentsRepository($this->pdo);
  }

  public function testItSavesCommentToDatabase(): void
  {
    $comment = new Comment(
      Uuid::random(),
      Uuid::random(),
      Uuid::random(),
      'Тестовый комментарий'
    );

    $this->repository->save($comment);

    $statement = $this->pdo->prepare(
      'SELECT * FROM comments WHERE uuid = :uuid'
    );
    $statement->execute([
      ':uuid' => (string)$comment->getUuid(),
    ]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $this->assertEquals((string)$comment->getUuid(), $result['uuid']);
    $this->assertEquals((string)$comment->getPostUuid(), $result['post_uuid']);
    $this->assertEquals((string)$comment->getAuthorUuid(), $result['author_uuid']);
    $this->assertEquals($comment->getText(), $result['text']);
  }

  public function testItFindsCommentByUuid(): void
  {
    $uuid = Uuid::random();
    $postUuid = Uuid::random();
    $authorUuid = Uuid::random();

    $this->pdo->exec(
      "INSERT INTO comments (uuid, post_uuid, author_uuid, text) 
             VALUES ('$uuid', '$postUuid', '$authorUuid', 'Текст комментария')"
    );

    $comment = $this->repository->get($uuid);

    $this->assertEquals($uuid, $comment->getUuid());
    $this->assertEquals($postUuid, $comment->getPostUuid());
    $this->assertEquals($authorUuid, $comment->getAuthorUuid());
    $this->assertEquals('Текст комментария', $comment->getText());
  }

  public function testItThrowsExceptionWhenCommentNotFound(): void
  {
    $uuid = Uuid::random();

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Комментарий не найден: $uuid");

    $this->repository->get($uuid);
  }
}
