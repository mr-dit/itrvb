<?php

namespace App\Tests;

use App\Exceptions\UserNotFoundException;
use App\Commands\CreatePost;
use App\Repositories\PostsRepository;
use App\Repositories\UsersRepository;
use App\UUID;
use PHPUnit\Framework\TestCase;

class CreatePostTest extends TestCase
{
  private function createStubs(): array
  {
    return [
      'postsRepository' => $this->createStub(PostsRepository::class),
      'usersRepository' => $this->createStub(UsersRepository::class),
    ];
  }

  public function testItSuccessfullyCreatesPost(): void
  {
    $stubs = $this->createStubs();
    $command = new CreatePost($stubs['postsRepository'], $stubs['usersRepository']);

    $this->assertTrue($command->handle([
      'author_uuid' => Uuid::random(),
      'title' => 'Title',
      'text' => 'Text'
    ]));
  }

  public function testItThrowsExceptionIfUuidInvalid(): void
  {
    $stubs = $this->createStubs();
    $command = new CreatePost($stubs['postsRepository'], $stubs['usersRepository']);

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid UUID');

    $command->handle([
      'author_uuid' => 'invalid-uuid',
      'title' => 'Title',
      'text' => 'Text'
    ]);
  }

  public function testItThrowsExceptionIfUserNotFound(): void
  {
    $stubs = $this->createStubs();

    $authorUuid = Uuid::random();

    $stubs['usersRepository']
      ->expects($this->once())
      ->method('get')
      ->with($authorUuid)
      ->willThrowException(
        new UserNotFoundException()
      );

    $createPost = new CreatePost(
      $stubs['postsRepository'],
      $stubs['usersRepository']
    );

    $this->expectException(UserNotFoundException::class);
    $this->expectExceptionMessage('User not found');

    $createPost->handle([
      'author_uuid' => (string)$authorUuid,
      'title' => 'Заголовок',
      'text' => 'Текст'
    ]);
  }

  public function testItThrowsExceptionIfDataIncomplete(): void
  {
    $stubs = $this->createStubs();
    $command = new CreatePost($stubs['postsRepository'], $stubs['usersRepository']);

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing required fields');

    $command->handle([
      'author_uuid' => Uuid::random(),
      'title' => 'Title'
    ]);
  }
}
