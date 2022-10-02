<?php

namespace App\Repositories;

use App\Blog\Article;

interface ArticleRepositoryInterface
{
    public function save(Article $article): void;
    public function get(int $id): Article;
    public function getAllArticleId(): array; //для проверки существует ли id article для comment

}