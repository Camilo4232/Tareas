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


$router->post('/tareas', 'TareaController@create');
$router->put('/tareas/{id}', 'TareaController@update');
$router->delete('/tareas/{id}', 'TareaController@delete');
$router->get('/tareas', 'TareaController@list');
$router->patch('/tareas/{id}/status', 'TareaController@changeStatus');
$router->patch('/tareas/{id}/reassign', 'TareaController@reassign');
