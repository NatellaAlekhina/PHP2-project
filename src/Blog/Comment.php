<?php

namespace App\Blog;

use App\Blog\Article;
use App\Traits\Id;
use App\Traits\UserId;
use App\User\User;

class Comment
{
    use Id;
    use UserId;

    private User $userNameComment;
    private Article $articleName;

    public function __construct(
        private int $userId,
        private string $articleId,
        private string $text)
    {}

    public function __toString(): string
    {
        return 'Пользователь ' . $this->userNameComment->getName() . ' ' . $this->userNameComment->getSurname() .
            ' ' . 'к статье с заголовком ' . $this->articleName->getHeading() . ' оставил комментарий: ' .
            $this->text.PHP_EOL;
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

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

}