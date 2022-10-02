<?php

namespace App\Handlers;

use App\Exceptions\CommentNotFoundException;
use App\Repositories\CommentRepositoryInterface;
use App\Request\Request;
use APP\Response\AbstractResponse;
use APP\Response\ErrorResponse;
use APP\Response\SuccessResponse;
use Exception;

class CommentSearchHandler implements CommentSearchHandlerInterface
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $commentId = $request->query('id');
        } catch (Exception $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        try {

            $comment = $this->commentRepository->get($commentId);

        } catch (CommentNotFoundException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'id' => $comment->getId(),
                'comment' => $comment->getText()
            ]
        );

    }
}