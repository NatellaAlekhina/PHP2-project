<?php

namespace App;
use App\User;

class Article
{
    public function __construct(
        private int $id,
        private int $userId,
        private string $heading,
        private string $text)
    {}

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


}