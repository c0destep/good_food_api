<?php

namespace App\Entity;

use Framework\Date\Date;
use Framework\MVC\Entity;

class UserEntity extends Entity
{
    protected int $id;
    protected string $email;
    protected string $password;
    protected Date $createdAt;
    protected Date $updatedAt;
    protected array $_jsonVars = [
        'id', 'email', 'password',
        'createdAt', 'updatedAt'
    ];
}
