<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('api', function($routes){

    $routes->post('auth/register', 'API\AuthController::register');
    $routes->post('auth/login', 'API\AuthController::login');
    $routes->post('auth/logout', 'API\AuthController::logout');

});

$routes->group('api', ['filter'=>'auth'], function($routes){

    $routes->post('chat/send', 'API\ChatController::send');

});

// páginas MVC
$routes->get('/', 'ChatPageController::login');
$routes->get('login', 'ChatPageController::login');
$routes->get('chat', 'ChatPageController::chat');
$routes->get('logout', 'ChatPageController::logout');