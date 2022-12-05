<?php

namespace App\Entity;

use Framework\Date\Date;
use Framework\MVC\Entity;

class AddressEntity extends Entity
{
    protected int $id;
    protected string $idRestaurant;
    protected string $state;
    protected string $city;
    protected ?string $district = null;
    protected string $address;
    protected int $number;
    protected string $complement;
    protected Date $createdAt;
    protected Date $updatedAt;
    protected array $_jsonVars = [
        'state', 'city', 'district',
        'address', 'number', 'complement',
        'createdAt', 'updatedAt'
    ];
}
