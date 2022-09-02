<?php

use App\Article;
use App\Comment;
use App\User;

require_once __DIR__ . '/vendor/autoload.php';

$user = new User(1, 'Ivan', 'Petrov');
$article = new Article(1, $user->getId(), "my first article","what a nice day");
$comment = new Comment(1, $user->getId(), $article->getId(), "good article!");

print_r($user);
print_r($article);
print_r($comment);

