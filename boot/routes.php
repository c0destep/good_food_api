<?php
/*
 * This file is part of Good food API.
 *
 * (c) Lucas Alves <codestep@codingstep.com.br>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Framework\Routing\RouteCollection;

App::router()->serve('http://localhost:8080', static function (RouteCollection $routes): void {
    $routes->group('/api', [
        $routes->post('/login', 'App\AuthController::login', 'auth.login'),
        $routes->post('/register', 'App\AuthController::login', 'auth.register'),

        $routes->resource('/users', 'App\Users', 'users'),

        $routes->resource('/restaurants', 'App\Restaurants', 'restaurants'),

        $routes->group('/restaurants/{int}', [
            $routes->resource('/products', 'App\Products', 'products')
        ])
    ]);

    $routes->notFound(static fn() => not_found());
});
