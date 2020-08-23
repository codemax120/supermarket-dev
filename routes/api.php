<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ROUTE FOR LOGIN AND REGISTER
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('user', 'UserController@user');
    Route::get('users/info', 'UserController@updateInfo');
    Route::get('users/password', 'UserController@updatePassword');

    // ROUTES RESOURCE FOR USERS
    Route::apiResource('users', 'UserController');
    // ROUTES RESOURCE FOR ROLES
    Route::apiResource('roles', 'RoleController');
    // ROUTES RESOURCE FOR SUPERMARKET
    Route::apiResource('supermarkets', 'RoleController');

});
