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

$router->post('/register', 'UserController@create');
$router->post('/login', 'UserController@authenticate');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/user', 'UserController@show');

    $router->get('/todos', 'TodoController@index');
    $router->post('/todos/create', 'TodoController@create');
    $router->put('/todos/{id}/complete', 'TodoController@complete');
    $router->put('/todos/{id}/in-complete', 'TodoController@inComplete');
    $router->delete('/todos/{id}', 'TodoController@delete');

    $router->get('/users/{user_id}/todos', 'TodoController@showUserTodos');
});

