<?php

namespace App\myHttp\Actions\Posts;

use App\myHttp\Actions\ActionInterface;
use App\myHttp\ErrorResponse;
use App\myHttp\Request;
use App\myHttp\Response;
use App\myHttp\SuccessfullResponse;
use App\Post;
use App\UUID;
use App\Repositories\PostsRepository;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepository $postRepository
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $data = $request->body(['author_uuid', 'title', 'text']);

            $this->validateTitleAndText($data['title'], $data['text']);

            $post = $this->createPost($data);

            $this->postRepository->save($post);

            return new SuccessfullResponse(['message' => 'Post created successfully']);
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }

    private function validateTitleAndText(string $title, string $text): void
    {
        if (empty($title) || empty($text)) {
            throw new \InvalidArgumentException('Title or text cannot be empty');
        }
    }

    private function createPost(array $data): Post
    {
        return new Post(Uuid::random(), new Uuid($data['author_uuid']), $data['title'], $data['text']);
    }
}
