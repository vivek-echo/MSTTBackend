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
    
    Route::match(['GET', 'POST'], 'checkUser', [App\Http\Controllers\AuthController::class, 'checkUser']);
    Route::match(['GET', 'POST'], 'validateOtp', [App\Http\Controllers\AuthController::class, 'validateOtp']);
    Route::match(['GET', 'POST'], 'login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('signUp',[App\Http\Controllers\AuthController::class, 'signUp']);
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');


    Route::match(['GET', 'POST'], 'addCar', [App\Http\Controllers\CarBooking\AddCarController::class, 'addCar']);

});
