<?php

namespace App\Models;

use App\Entity\ProductEntity;
use Framework\MVC\Model;

class ProductsModel extends Model
{
    protected string $returnType = ProductEntity::class;
    protected string $table = 'products';
    protected bool $autoTimestamps = true;
    protected array $allowedFields = [
        'idRestaurant', 'image', 'name',
        'price', 'description', 'isPromotion',
        'promotionDescription', 'promotionPrice',
        'promotionTime'
    ];
    protected array $validationRules = [
        'image' => 'required|image|ext:jpg,png',
        'name' => 'required|string|maxLength:256',
        'price' => 'required|float|greater:{0}',
        'description' => 'required|string|maxLength:256',
        'isPromotion' => 'optional|in:0,1',
        'promotionDescription' => 'optional|string|maxLength:256',
        'promotionPrice' => 'optional|float|greater:{0}',
        'promotionTime' => 'optional|string|maxLength:256'
    ];
    protected bool $cacheActive = true;

    protected function getValidationLabels(): array
    {
        return $this->validationLabels ??= [
            'image' => $this->getLanguage()->render('products', 'image'),
            'name' => $this->getLanguage()->render('products', 'name'),
            'price' => $this->getLanguage()->render('products', 'price'),
            'description' => $this->getLanguage()->render('products', 'description'),
            'isPromotion' => $this->getLanguage()->render('products', 'isPromotion'),
            'promotionDescription' => $this->getLanguage()->render('products', 'promotionDescription'),
            'promotionPrice' => $this->getLanguage()->render('products', 'promotionPrice'),
            'promotionTime' => $this->getLanguage()->render('products', 'promotionTime')
        ];
    }
}
