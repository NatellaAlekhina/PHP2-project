<?php

namespace App\Blog;
use App\Traits\Id;
use App\Traits\UserId;
use App\User\User;

class Article
{
    use Id;
    use UserId;

    private User $userName;

    public function __construct(
        private int $userId,
        private string $heading,
        private string $text
    )
    {
    }

    public function __toString(): string
    {
        return $this->userName->getName() . ' Article.php' . $this->userName->getSurname() . ' в статье ' . $this->heading .
            ' пишет: ' . $this->text.PHP_EOL;
    }

    public function getHeading(): string
    {
        return $this->heading;
    }


    public function setHeading(string $heading): self
    {
        $this->heading = $heading;

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