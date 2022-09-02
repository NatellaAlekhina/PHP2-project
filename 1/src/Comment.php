<?php

namespace App;
use App\User;
use App\Article;

class Comment
{
    public function __construct(
        private int $id,
        private int $userId,
        private string $articleId,
        private string $text)
    {}
}