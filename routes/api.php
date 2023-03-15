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

Route::group([

    'middleware' => 'api'

], function () {
    
    Route::post('login',[App\Http\Controllers\AuthController::class, 'login']);
    Route::post('signUp',[App\Http\Controllers\AuthController::class, 'signUp']);
    
    // Route::post('login', function(){
    //     return "vcsjnchjsn";
    // });
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
