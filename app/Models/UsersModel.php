<?php

namespace App\Models;

use App\Entity\UserEntity;
use Framework\MVC\Model;

class UsersModel extends Model
{
    protected string $returnType = UserEntity::class;
    protected string $table = 'users';
    protected bool $autoTimestamps = true;
    protected array $allowedFields = [
        'email', 'password'
    ];
    protected array $validationRules = [];
    protected bool $cacheActive = true;

    public function getUserByEmail(string $email): ?object
    {
        $user = $this->getDatabaseToRead()
            ->select()
            ->from($this->getTable())
            ->whereEqual('email', $email);
        return $user->run()->fetch();
    }
}
