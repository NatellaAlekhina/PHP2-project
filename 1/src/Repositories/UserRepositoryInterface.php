<?php

namespace App\Repositories;

use App\User\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function get(int $id): User;
    public function getAllUsersId(): array; //для проверки существует ли id user для article и comment
}