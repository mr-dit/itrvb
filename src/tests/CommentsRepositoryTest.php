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

    // Очистка таблицы перед каждым тестом
    $this->pdo->exec('DELETE FROM comments WHERE uuid = "00000000-0000-0000-0000-000000000001"');

    $this->repository = new CommentsRepository($this->pdo);
  }

  public function testItSavesCommentToRepository(): void
  {
    $testUuid = new Uuid('00000000-0000-0000-0000-000000000001');
    $postUuid = new Uuid('00000000-0000-0000-0000-000000000002');
    $authorUuid = new Uuid('00000000-0000-0000-0000-000000000003');

    $comment = new Comment(
      $testUuid,
      $postUuid,
      $authorUuid,
      'Тестовый комментарий'
    );

    $this->repository->save($comment);

    $statement = $this->pdo->prepare(
      'SELECT * FROM comments WHERE uuid = :uuid'
    );
    $statement->execute([
      ':uuid' => (string)$testUuid,
    ]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $this->assertEquals((string)$testUuid, $result['uuid']);
    $this->assertEquals((string)$postUuid, $result['post_uuid']);
    $this->assertEquals((string)$authorUuid, $result['author_uuid']);
    $this->assertEquals('Тестовый комментарий', $result['text']);
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
    $this->assertEquals('Текст комментария', $comment->getContent());
  }

  public function testItThrowsExceptionWhenCommentNotFound(): void
  {
    $uuid = Uuid::random();

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Комментарий не найден: $uuid");

    $this->repository->get($uuid);
  }
}
