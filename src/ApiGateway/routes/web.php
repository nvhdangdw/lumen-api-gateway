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


$router->get('/api/{host}/{path:.*}', 'BaseController@request');
$router->put('/api/{host}/{path:.*}', 'BaseController@request');
$router->post('/api/{host}/{path:.*}', 'BaseController@request');
$router->delete('/api/{host}/{path:.*}', 'BaseController@request');
