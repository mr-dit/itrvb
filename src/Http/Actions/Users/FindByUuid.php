<?php

namespace App\myHttp\Actions\Users;

use App\myHttp\Actions\ActionInterface;
use App\myHttp\ErrorResponse;
use App\myHttp\SuccessfullResponse;
use App\myHttp\Request;
use App\myHttp\Response;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UsersRepository;
use App\UUID;

class FindByUuid implements ActionInterface
{
    public function __construct(
        private UsersRepository $userRepository
    ) {}

    public function handle(Request $request): Response
    {
        $userUuid = $request->query('uuid');

        if (empty($userUuid)) {
            return new ErrorResponse('Username parameter is missing in the request');
        }

        try {
            $user = $this->userRepository->get(new Uuid($userUuid));
            return new SuccessfullResponse([
                'username' => $user->getNickname(),
                'name' => (string)$user->getFirstName()
            ]);
        } catch (UserNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }
}
