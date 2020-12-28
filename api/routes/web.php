<?php

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->get('/user', 'UserController@get_user');

$router->post('/products', 'ProductController@store');
$router->get('/products[/{id}]', 'ProductController@show');

$router->post('/store', 'StoreController@store');
$router->get('/store', 'StoreController@get_store');

$router->post('/category', 'CategoryController@store');
$router->delete('/category[/{id}]', 'CategoryController@destroy');

$router->post('/token-store', 'StoreController@create_token');

$router->post('/cart', 'CartController@store');
$router->get('/logout', 'AuthController@logout');