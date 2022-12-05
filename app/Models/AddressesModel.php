<?php

namespace App\Models;

use App\Entity\AddressEntity;
use Framework\MVC\Model;

class AddressesModel extends Model
{
    protected string $returnType = AddressEntity::class;
    protected string $table = 'addresses';
    protected bool $autoTimestamps = true;
    protected array $allowedFields = [
        'idRestaurant', 'state', 'city',
        'district', 'address', 'number',
        'complement'
    ];
    protected array $validationRules = [];
    protected bool $cacheActive = true;
}
