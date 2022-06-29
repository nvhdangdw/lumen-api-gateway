<?php

declare(strict_types=1);

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

$router->post('/register', 'UserController@register');

$router->group(['prefix' => 'api', 'middleware' => 'client.credentials'], function () use ($router) {
    $router->group(['prefix' => 'qr-benefit', 'middleware' => 'scope:qr-benefit'], function () use ($router) {
        $router->post('/login', ['uses' => 'QRBenefitController@login']);
        $router->get('/info', ['uses' => 'QRBenefitController@info']);
    });

    $router->group(['prefix' => 'product', 'middleware' => 'scope:product'], function () use ($router) {
        $router->get('/', ['uses' => 'ProductController@index']);
        $router->post('/', ['uses' => 'ProductController@store']);
        $router->get('/{product}', ['uses' => 'ProductController@show']);
        $router->patch('/{product}', ['uses' => 'ProductController@update']);
        $router->delete('/{product}', ['uses' => 'ProductController@destroy']);
    });

    $router->group(['prefix' => 'order', 'middleware' => 'scope:order'], function () use ($router) {
        $router->get('/', ['uses' => 'OrderController@index']);
        $router->post('/', ['uses' => 'OrderController@store']);
        $router->get('/{order}', ['uses' => 'OrderController@show']);
        $router->patch('/{order}', ['uses' => 'OrderController@update']);
        $router->delete('/{order}', ['uses' => 'OrderController@destroy']);
    });
});
