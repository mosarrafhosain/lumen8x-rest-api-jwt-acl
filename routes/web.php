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

$router->group(['prefix' => 'api'], function () use ($router) {
    //$router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');

        // Role
        $router->get('/roles', 'RoleController@index');
        $router->get('/roles/create', 'RoleController@create');
        $router->post('/roles', 'RoleController@store');
        $router->get('/roles/{id}', 'RoleController@show');
        $router->get('/roles/{id}/edit', 'RoleController@edit');
        $router->put('/roles/{id}', 'RoleController@update');
        $router->delete('/roles/{id}', 'RoleController@destroy');

        // User
        $router->get('/users', 'UserController@index');
        $router->get('/users/create', 'UserController@create');
        $router->post('/users', 'UserController@store');
        $router->get('/users/{id}', 'UserController@show');
        $router->get('/users/{id}/edit', 'UserController@edit');
        $router->put('/users/{id}', 'UserController@update');
        $router->delete('/users/{id}', 'UserController@destroy');

        // Post
        $router->get('/posts', 'PostController@index');
        $router->get('/posts/create', 'PostController@create');
        $router->post('/posts', 'PostController@store');
        $router->get('/posts/{id}', 'PostController@show');
        $router->get('/posts/{id}/edit', 'PostController@edit');
        $router->put('/posts/{id}', 'PostController@update');
        $router->delete('/posts/{id}', 'PostController@destroy');

        // Product
        $router->get('/products', 'ProductController@index');
        $router->get('/products/create', 'ProductController@create');
        $router->post('/products', 'ProductController@store');
        $router->get('/products/{id}', 'ProductController@show');
        $router->get('/products/{id}/edit', 'ProductController@edit');
        $router->put('/products/{id}', 'ProductController@update');
        $router->delete('/products/{id}', 'ProductController@destroy');

        // Permission
        $router->get('/permissions', 'PermissionController@index');
        $router->get('/permissions/create', 'PermissionController@create');
        $router->post('/permissions', 'PermissionController@store');
        $router->get('/permissions/{id}', 'PermissionController@show');
        $router->get('/permissions/{id}/edit', 'PermissionController@edit');
        $router->put('/permissions/{id}', 'PermissionController@update');
        $router->delete('/permissions/{id}', 'PermissionController@destroy');
    });
});
