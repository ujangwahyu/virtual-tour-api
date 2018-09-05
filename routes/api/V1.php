
<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-03 15:41:34 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 10:52:05
 */

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', 'ExampleController@index');

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

    $router->group(['prefix' => 'tour'], function ($app) {
        $app->get('/', 'TourController@index');
        $app->get('/{id}', 'TourController@show');
        $app->post('/', 'TourController@store');
        $app->post('/{id}', 'TourController@update');
        $app->delete('/{id}', 'TourController@destroy');
    });

    $router->group(['prefix' => 'scene'], function ($app) {
        $app->get('/', 'SceneController@index');
        $app->get('/{id}', 'SceneController@show');
        $app->post('/', 'SceneController@store');
        $app->post('/{id}', 'SceneController@update');
        $app->delete('/{id}', 'SceneController@destroy');
    });

    $router->group(['prefix' => 'hotspot'], function ($app) {
        $app->get('/', 'HotspotController@index');
        $app->get('/{id}', 'HotspotController@show');
        $app->post('/', 'HotspotController@store');
        $app->post('/{id}', 'HotspotController@update');
        $app->delete('/{id}', 'HotspotController@destroy');
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