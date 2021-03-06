
<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-03 15:41:34 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-07 15:29:08
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

    $router->group(['prefix' => 'region'], function ($app) {
        $app->get('/', 'RegionController@index');
        $app->get('/{id}', 'RegionController@show');
        $app->post('/', 'RegionController@store');
        $app->put('/{id}', 'RegionController@update');
        $app->delete('/{id}', 'RegionController@destroy');
    });

    $router->group(['prefix' => 'tour'], function ($app) {
        $app->get('/', 'TourController@index');
        $app->get('/{id}', 'TourController@show');
        $app->post('/', 'TourController@store');
        $app->put('/{id}', 'TourController@update');
        $app->delete('/{id}', 'TourController@destroy');
        $app->get('/{id}/scene', 'SceneController@byTour');
    });

    $router->group(['prefix' => 'scene'], function ($app) {
        $app->get('/', 'SceneController@index');
        $app->get('/{id}', 'SceneController@show');
        $app->post('/', 'SceneController@store');
        $app->put('/{id}', 'SceneController@update');
        $app->delete('/{id}', 'SceneController@destroy');
        $app->get('/{id}/hotspot', 'HotspotController@byScene');
    });

    $router->group(['prefix' => 'hotspot'], function ($app) {
        $app->get('/', 'HotspotController@index');
        $app->get('/{id}', 'HotspotController@show');
        $app->post('/', 'HotspotController@store');
        $app->put('/{id}', 'HotspotController@update');
        $app->delete('/{id}', 'HotspotController@destroy');
        
    });

    $router->group(['prefix' => 'photo360'], function ($app) {
        $app->get('/', 'Photo360Controller@index');
        $app->get('/{id}', 'Photo360Controller@show');
        $app->post('/', 'Photo360Controller@store');
        $app->put('/{id}', 'Photo360Controller@update');
        $app->delete('/{id}', 'Photo360Controller@destroy');
    });

    $router->group(['prefix' => 'video360'], function ($app) {
        $app->get('/', 'Video360Controller@index');
        $app->get('/{id}', 'Video360Controller@show');
        $app->post('/', 'Video360Controller@store');
        $app->put('/{id}', 'Video360Controller@update');
        $app->delete('/{id}', 'Video360Controller@destroy');
    });
});

$router->group(['prefix' => 'superadmin', 'middleware' => 'jwt.auth'], function() use ($router) {
    $router->get('/user', function() {
        $users = \App\Models\User::where('role', 1)->get();
        return response()->json($users);
    });

    $router->group(['prefix' => 'user'], function ($app) {
        $app->post('/', 'UserController@register');
        $app->get('/', 'UserController@index'); 
        $app->get('/{id}', 'UserController@show'); 
        $app->put('/{id}', 'UserController@update');
        $app->delete('/{id}', 'UserController@destroy');
    });

    $router->group(['prefix' => 'region'], function ($app) {
        $app->get('/', 'RegionController@index');
        $app->post('/', 'RegionController@store'); 
        $app->get('/{id}', 'RegionController@show'); 
        $app->put('/{id}', 'RegionController@update');
        $app->delete('/{id}', 'RegionController@destroy');
    });

    $router->group(['prefix' => 'dashboard'], function ($app) {
        $app->get('/', 'DashboardController@index');
    });

});

$router->group(['prefix' => 'region'], function ($app) {
    $app->get('/', 'RegionController@indexClient');
    $app->get('/{id}', 'RegionController@showClient');
    $app->get('/{id}/tour', 'TourController@byRegion');
    $app->get('/{id}/photo360', 'Photo360Controller@byRegion');
    $app->get('/{id}/video360', 'Video360Controller@byRegion');
    $app->get('/{id}/newphoto360', 'Photo360Controller@newPhoto360');
    $app->get('/{id}/newvideo360', 'Video360Controller@newVideo360');
});

$router->group(['prefix' => 'tour'], function ($app) {
    $app->get('/', 'TourController@tourClient');
    $app->post('/', 'TourController@store');
    $app->get('/{id}', 'TourController@show'); 
    $app->get('/{id}/photo360', 'Photo360Controller@byTour');
});

$router->group(['prefix' => 'scene'], function ($app) {
    $app->get('/', 'SceneController@index');
    $app->get('/{id}', 'SceneController@show'); 
});

$router->group(['prefix' => 'hotspot'], function ($app) {
    $app->get('/', 'HotspotController@index'); 
    $app->get('/{id}', 'HotspotController@show');
    $app->post('/', 'HotspotController@store'); 
});

$router->group(['prefix' => 'photo360'], function ($app) {
    $app->get('/', 'Photo360Controller@index');
    $app->get('/{id}', 'Photo360Controller@show'); 
});

$router->group(['prefix' => 'video360'], function ($app) {
    $app->get('/', 'Video360Controller@index');
    $app->get('/{id}', 'Video360Controller@show');
});

$router->group(['prefix' => 'setting'], function ($app) {
    $app->get('/', 'SettingController@index');
    $app->get('/{id}', 'SettingController@show');
});


$router->group(['prefix' => 'user'], 
    function() use ($router) {
        $router->get('/', function() {
            $users = \App\Models\User::all();
            return response()->json($users);
        });
    }
);