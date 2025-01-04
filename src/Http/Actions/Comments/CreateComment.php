<?php

namespace App\myHttp\Actions\Comments;

use App\myHttp\Actions\ActionInterface;
use App\myHttp\ErrorResponse;
use App\myHttp\Request;
use App\myHttp\Response;
use App\myHttp\SuccessfullResponse;
use App\Comment;
use App\UUID;
use App\Repositories\CommentsRepository;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentsRepository $commentRepository
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $data = $request->body(['author_uuid', 'post_uuid', 'text']);
            $authorUuid = new Uuid($data['author_uuid']);
            $postUuid = new Uuid($data['post_uuid']);
            $text = $data['text'];

            $this->validateText($text);

            $comment = $this->createComment($authorUuid, $postUuid, $text);

            $this->commentRepository->save($comment);

            return new SuccessfullResponse(['message' => 'Comment created successfully']);
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }

    private function validateText(string $text): void
    {
        if (empty($text)) {
            throw new \InvalidArgumentException('Text cannot be empty');
        }
    }

    private function createComment(Uuid $authorUuid, Uuid $postUuid, string $text): Comment
    {
        return new Comment(Uuid::random(), $authorUuid, $postUuid, $text);
    }
}
