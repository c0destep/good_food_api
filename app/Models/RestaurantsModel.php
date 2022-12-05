<?php

namespace App\Models;

use App\Entity\RestaurantEntity;
use Framework\MVC\Model;
use Framework\Pagination\Pager;

class RestaurantsModel extends Model
{
    protected string $returnType = RestaurantEntity::class;
    protected string $table = 'restaurants';
    protected bool $autoTimestamps = true;
    protected array $allowedFields = [
        'image', 'name', 'businessHours'
    ];
    protected array $validationRules = [];
    protected bool $cacheActive = true;

    public function getRestaurantByName(string $name): ?object
    {
        $restaurant = $this->getDatabaseToRead()
            ->select()
            ->from($this->getTable())
            ->whereEqual('name', $name);
        return $restaurant->run()->fetch();
    }

    public function paginate(int $page, int $perPage = 10): array
    {
        $restaurants = $this->getDatabaseToRead()
            ->select()
            ->from($this->getTable())
            ->limit(...$this->makePageLimitAndOffset($page, $perPage))
            ->run()
            ->fetchArrayAll();

        foreach ($restaurants as $index => $restaurant) {
            $addresses = $this->getDatabaseToRead()
                ->select()
                ->from('addresses')
                ->whereEqual('idRestaurant', $restaurant['id'])
                ->run()
                ->fetchArrayAll();

            foreach ($addresses as $address) {
                $restaurants[$index]['addresses'][] = $address;
            }
        }

        $this->setPager(new Pager($page, $perPage, $this->count()));
        return $restaurants;
    }
}
