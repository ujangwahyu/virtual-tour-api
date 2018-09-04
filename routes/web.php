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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('/me', [
    'middleware' => 'jwt.auth',
    'uses' => 'UserController@me'
]);

// Authentication
$router->group(['prefix' => 'auth'], function ($app) {
    $app->post('/facebook', 'AuthController@loginFacebook');
    $app->post('/email', 'AuthController@loginEmail');
    $app->post('/register', 'AuthController@register');
    $app->post('/logout', [
        'middleware' => 'jwt.auth',
        'uses' => 'AuthController@logout'
    ]);
});

$router->group(['prefix' => 'admin', 'middleware' => 'jwt.auth'], function() use ($router) {
    $router->get('/user', function() {
        $users = \App\Models\User::all();
        return response()->json($users);
    });

    $router->group(['prefix' => 'technology'], function ($app) {
        $app->get('/', 'TechnologyController@index');
        $app->get('/{id}', 'TechnologyController@show');
        $app->post('/', 'TechnologyController@store');
        $app->post('/{id}', 'TechnologyController@update');
        $app->delete('/{id}', 'TechnologyController@destroy');
    });
});

$router->group(['prefix' => 'daerah'], function ($app) {
    $app->get('/', 'DaerahController@index');
    $app->get('/{id}', 'DaerahController@show');
});

$router->group(['prefix' => 'user'], 
    function() use ($router) {
        $router->get('/', function() {
            $users = \App\Models\User::all();
            return response()->json($users);
        });
    }
);