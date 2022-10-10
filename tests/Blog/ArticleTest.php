<?php

namespace Test\Blog;

use App\Blog\Article;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\ArticleRepository;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/..'.'/../public/autoloader_runtime.php';

class ArticleTest extends TestCase
{
    public function testItThrowExceptionWhenUserNotFoundForArticle(): void
    {
        //Podgotovka
        $article = new Article(100,'some_heading', 'some_text');
        $articleRepository = new ArticleRepository();

        //Type ogidaemogo iskluycheniya
        $this->expectException(UserNotFoundException::class);

        //Sobitie
        $this->expectExceptionMessage("User with id:100 not found, 
    can't save the article!");
        //deistvie k polucheniu oshibki
        $articleRepository->save($article);
    }

    public function testGetArticleByIdException(): void
    {
        $articleId = 200;

        $articleRepository = new ArticleRepository();

        $this->expectException(ArticleNotFoundException::class);

        $this->expectExceptionMessage("Article with id:$articleId not found".PHP_EOL);

        $articleRepository->get($articleId);
    }

    public function testGetArticleByIdObject(): void
    {
        $articleRepository = new ArticleRepository();
        $article = new Article(1, 'some_heading_test', 'find_me');
        $articleRepository->save($article);
        $article->setId(28);
        $value = $articleRepository->get(28);

        $this->assertEquals($article, $value);

    }
}