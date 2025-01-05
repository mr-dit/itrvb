<?php

require 'vendor/autoload.php';

use App\myHttp\Actions\Comments\CreateComment;
use App\myHttp\Actions\Like\AddLike;
use App\myHttp\Actions\Posts\CreatePost;
use App\myHttp\Actions\Posts\DeletePost;
use App\myHttp\Actions\Users\FindByUuid;
use App\myHttp\ErrorResponse;
use App\myHttp\Request;
use App\Repositories\CommentsRepository;
use App\Repositories\LikeRepository;
use App\Repositories\PostsRepository;
use App\Repositories\UsersRepository;
use PDO;


ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $request = new Request($_GET, $_POST, $_SERVER);
} catch (Exception $ex) {
    handleError($ex->getMessage());
}

try {
    $path = $request->path();
    $method = $request->method();
} catch (Exception $ex) {
    handleError($ex->getMessage());
}

$pdo = new PDO('sqlite:' . __DIR__ . '/../../database.sqlite');

$routes = [
    'GET' => [
        '/users/show' => new FindByUuid(
            new UsersRepository($pdo)
        )
    ],
    'POST' => [
        '/posts/comment' => new CreateComment(
            new CommentsRepository($pdo)
        ),
        '/posts/create' => new CreatePost(
            new PostsRepository($pdo)
        ),
        '/posts/like' => new AddLike(
            new LikeRepository($pdo)
        )
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new PostsRepository($pdo)
        )
    ]
];

if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
    handleError('Not found path');
}

$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (Exception $ex) {
    handleError($ex->getMessage());
}

$response->send();

function handleError($message)
{
    (new ErrorResponse($message))->send();
    exit();
}
