<?php

require 'vendor/autoload.php';

use App\myHttp\Actions\Comments\CreateComment;
use App\myHttp\Actions\Posts\CreatePost;
use App\myHttp\Actions\Posts\DeletePost;
use App\myHttp\Actions\Users\FindByUsername;
use App\myHttp\ErrorResponse;
use App\myHttp\Request;
use App\Repositories\CommentsRepository;
use App\Repositories\PostsRepository;
use App\Repositories\UsersRepository;

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

$routes = [
    'GET' => [
        '/users/show' => new FindByUsername(
            new UsersRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        )
    ],
    'POST' => [
        '/posts/comment' => new CreateComment(
            new CommentsRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        ),
        '/posts/create' => new CreatePost(
            new PostsRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        )
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new PostsRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
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
