<?php

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

Route::post('register', 'API\RegisterController@register');
Route::post('login', [ 'as' => 'login', 'uses' => 'API\RegisterController@login']);
Route::patch('users/{user}/set-currency', [ 'as' => 'setCurrency', 'uses' => 'API\RegisterController@update']);

Route::middleware('auth:api')->group( function () {
    Route::resource('users.transactions', 'API\UserTransactionController',['only'=> ['index', 'store']]);
});
