<?php
/*
 * This file is part of Good food API.
 *
 * (c) Lucas Alves <codestep@codingstep.com.br>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Controllers\AuthenticationController;
use App\Controllers\RestaurantController;
use App\Controllers\UserController;
use Framework\Routing\RouteCollection;

App::router()->serve('http://localhost:8080', static function (RouteCollection $routes): void {
    $routes->group('/api', [
        $routes->post('/login', [AuthenticationController::class, 'login'], 'authentication.login'),
        $routes->post('/register', [AuthenticationController::class, 'register'], 'authentication.register'),

        $routes->resource('/users', UserController::class, 'users'),

        $routes->resource('/restaurants', RestaurantController::class, 'restaurants'),

        $routes->group('/restaurants/{int}', [
            $routes->resource('/products', RestaurantController::class, 'products'),
        ]),
    ]);

    $routes->namespace('App\Controllers', [
        $routes->get('/', 'Home::index', 'home'),
    ]);

    $routes->notFound(static fn() => not_found());
});
