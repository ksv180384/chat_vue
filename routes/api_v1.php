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

// Авторизация
Route::post('login', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'logout']);
Route::post('registration', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'registration']);
//Route::post('refresh', 'AuthController@refresh');
Route::get('me', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'me']);


// Пользователь
Route::get('/users', [\App\Http\Controllers\Api\V1\UserController::class, 'index']);

// Чат
Route::group(['middleware' => 'auth', 'prefix' => 'chat'], function (){
    Route::get('/', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'show']);
    Route::post('/create', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'store']);
    Route::post('/join', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'join']);
    Route::post('/lave', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'lave']);

    Route::post('/send-message', [\App\Http\Controllers\Api\V1\Chat\MessageController::class, 'store']);
});

// Пользователи
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function (){
    Route::get('search/{user}', [\App\Http\Controllers\Api\V1\UserController::class, 'search']);
});

