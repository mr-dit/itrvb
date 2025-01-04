<?php

namespace App\myHttp\Actions\Posts;

use App\myHttp\Actions\ActionInterface;
use App\myHttp\ErrorResponse;
use App\myHttp\Request;
use App\myHttp\Response;
use App\myHttp\SuccessfullResponse;
use App\Exceptions\PostNotFoundException;
use App\Repositories\PostsRepository;
use App\UUID;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostsRepository $postRepository
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');

            if (empty($postUuid)) {
                throw new \Exception('UUID parameter is missing in the request');
            }

            $this->postRepository->delete(new Uuid($postUuid));

            return new SuccessfullResponse(['message' => 'Post deleted successfully']);
        } catch (\Exception | PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}
