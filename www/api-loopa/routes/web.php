<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return [
            'status' => 'success',
            'message' => 'Bem Vindo API Loopa',
            'data' => [
                'version' => '1.0.0',
                'author' => 'Edson Lourenço B. Filho',
                'email' => 'edcastanha@gmail.com'
            ]
        ];
    });
});