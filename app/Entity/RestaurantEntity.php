<?php

namespace App\Entity;

use Framework\Date\Date;
use Framework\MVC\Entity;

class RestaurantEntity extends Entity
{
    protected int $id;
    protected string $image;
    protected string $name;
    protected string $businessHours;
    protected Date $createdAt;
    protected Date $updatedAt;
    protected array $_jsonVars = [
        'id', 'image', 'name', 'businessHours',
        'createdAt', 'updatedAt'
    ];
}
