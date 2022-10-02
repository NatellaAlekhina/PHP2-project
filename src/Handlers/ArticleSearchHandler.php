<?php

namespace App\Handlers;

use App\Exceptions\ArticleNotFoundException;
use App\Repositories\ArticleRepositoryInterface;
use App\Request\Request;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use Exception;
use APP\Response\AbstractResponse;

class ArticleSearchHandler implements ArticleSearchHandlerInterface
{
    public function __construct(private ArticleRepositoryInterface $articleRepository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $articleId = $request->query('id');
        } catch (Exception $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        try {

            $article = $this->articleRepository->get($articleId);

        } catch (ArticleNotFoundException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'id' => $article->getId(),
                'article' => $article->getHeading() . ' ' . $article->getText()
            ]
        );

    }

}