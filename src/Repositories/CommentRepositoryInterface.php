<?php

namespace App\Repositories;

use App\Blog\Comment;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;

    public function get(int $id): Comment;
}