<?php
namespace App\User;

use App\Date\DateTime;
use App\Traits\Active;
use App\Traits\CreatedAt;
use App\Traits\DeleteAt;
use App\Traits\Id;
use App\Traits\UpdateAt;

class User
{
    use Id;
    use Active;
    use CreatedAt;
    use UpdateAt;
    use DeleteAt;


    public function __construct(
        //private int $id,
        private string $name,
        private string $surname)
    {
        $this->createdAt = new DateTime();
    }

    public function __toString(): string
    {
        return 'Пользователь ' . $this->name . ' ' . $this->surname . ' '.
            '(на сайте с ' . $this->createdAt->format('Y-m-d H:i:s') . ')'.PHP_EOL;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }


}