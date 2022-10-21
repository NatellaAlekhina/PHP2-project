<?php

namespace App\Repositories;

use App\Blog\Like;

interface LikeRepositoryInterface
{
    //public function save(Like $like): void;

    public function get(int $id): Like;
}