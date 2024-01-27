<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

$router->group(['namespace' => '\App\Http\Controllers\v1', 'prefix' => 'v1',], function() use ($router){
    // Auth
    $router->post('register', 'UserController@register');
    $router->post('login', 'UserController@login');
    $router->post('refresh-token', 'UserController@refreshToken');
    $router->post('forgot/password', 'UserController@forgotPassword');
    $router->post('reset/password', 'UserController@resetPassword');

    //frontend version
    $router->get('update', 'TechnicalController@getUpdate');

    $router->group(['middleware' => 'EnsureTokenIsValid'], function() use ($router){
        // User
        $router->get('profile', 'UserController@profile');
        $router->post('change/password', 'UserController@changePassword');
    });
});
