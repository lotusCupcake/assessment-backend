<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/register', '\App\Apis\AuthController::register');
$routes->post('/login', '\App\Apis\AuthController::login');
$routes->post('/refresh', '\App\Apis\AuthController::refreshToken');
$routes->post('/topup', '\App\Apis\TransactionsController::topup', ['filter' => 'JWTAuth']);

