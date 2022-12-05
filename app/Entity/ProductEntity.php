<?php

namespace App\Entity;

use Framework\Date\Date;
use Framework\MVC\Entity;

class ProductEntity extends Entity
{
    protected int $id;
    protected string $idRestaurant;
    protected string $image;
    protected string $name;
    protected float $price;
    protected string $description;
    protected int $isPromotion = 0;
    protected string $promotionDescription;
    protected float $promotionPrice;
    protected string $promotionTime;
    protected Date $createdAt;
    protected Date $updatedAt;
    protected array $_jsonVars = [
        'image', 'name', 'price',
        'description', 'isPromotion',
        'promotionDescription', 'promotionPrice',
        'promotionTime', 'createdAt', 'updatedAt'
    ];
}
