<?php

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

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(['prefix' => 'order'], function () use ($router) {
        $router->get('/', ['uses' => 'OrderController@index']);
        $router->post('/', ['uses' => 'OrderController@store']);
        $router->get('/{order}', ['uses' => 'OrderController@show']);
        $router->patch('/{order}', ['uses' => 'OrderController@update']);
        $router->delete('/{order}', ['uses' => 'OrderController@destroy']);
    });

});