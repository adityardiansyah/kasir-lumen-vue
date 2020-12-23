<?php

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->get('/user', 'UserController@get_user');

$router->post('/products', 'ProductController@store');
$router->get('/products[/{id}]', 'ProductController@show');

$router->post('/store', 'StoreController@store');

$router->post('/category', 'CategoryController@store');

$router->post('/token-store', 'StoreController@create_token');