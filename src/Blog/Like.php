<?php

namespace App\Blog;

use App\Traits\Id;
use App\Traits\UserId;
use App\User\User;

class Like
{
    use Id;
    use UserId;

    private User $userNameComment;
    private Article $articleName;

    public function __construct(
        private int $userId,
        private int $articleId
        )
    {}

    public function __toString(): string
    {
        return 'Пользователь ' . $this->userNameComment->getName() . ' ' . $this->userNameComment->getSurname() .
            ' ' . 'к статье с заголовком ' . $this->articleName->getHeading() . ' оставил лайк'.PHP_EOL;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function setArticleId(int $articleId): self
    {
        $this->articleId = $articleId;

        return $this;
    }

}