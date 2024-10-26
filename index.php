<?php

require_once 'autoload.php';
require_once 'vendor/autoload.php';

use MyApp\User;
use MyApp\Article;
use MyApp\Comment;
use Faker\Factory as Faker;

$faker = Faker::create();

$user = new User($faker->randomNumber(), $faker->firstName, $faker->lastName);
echo "User: {$user->id}, {$user->firstName} {$user->lastName}\n";

$article = new Article($faker->randomNumber(), $user->id, $faker->sentence, $faker->paragraph);
echo "Article: {$article->id}, Author: {$article->authorId}, Title: {$article->title}\n";

$comment = new Comment($faker->randomNumber(), $user->id, $article->id, $faker->text);
echo "Comment: {$comment->id}, Author: {$comment->authorId}, Article: {$comment->articleId}, Text: {$comment->text}\n";
