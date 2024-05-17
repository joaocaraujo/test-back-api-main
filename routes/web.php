<?php

$router->get('/api-docs', 'Swagger\SwaggerController@docs');

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('/', 'User\UserController@all');
    $router->get('/spreadsheet', 'User\UserController@createSpreadsheet');
    $router->post('/spreadsheet', 'User\UserController@spreadsheet');
    $router->get('/{id}', 'User\UserController@show');
    $router->patch('/{id}/name', 'User\UserController@editName');
    $router->patch('/{id}/cpf', 'User\UserController@editCpf');
    $router->patch('/{id}/email', 'User\UserController@editEmail');
});