<?php

namespace App\myHttp\Actions\Like;

use App\myHttp\Actions\ActionInterface;
use App\myHttp\Request;
use App\myHttp\Response;
use App\Repositories\LikeRepository;
use App\myHttp\SuccessfullResponse;
use App\myHttp\ErrorResponse;
use App\UUID;
use App\Like;

class AddLike implements ActionInterface
{
  private LikeRepository $likeRepository;
  public function __construct(LikeRepository $likeRepository)
  {
    $this->likeRepository = $likeRepository;
  }
  public function handle(Request $request): Response
  {
    try {
      $postUuid = new Uuid($request->get('post_uuid'));
      $userUuid = new Uuid($request->get('user_uuid'));

      if (!Uuid::isValid($postUuid) || !Uuid::isValid($userUuid)) {
        return new ErrorResponse('Некорректные UUID для статьи или пользователя.');
      }

      $like = new Like(Uuid::random(), $postUuid, $userUuid);
      $this->likeRepository->save($like);
      return new SuccessfullResponse([
        'success' => true,
        'message' => 'Лайк успешно добавлен.',
      ]);
    } catch (\Exception $e) {
      return new ErrorResponse($e->getMessage());
    }
  }
}
