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


//Route::post('refresh', 'AuthController@refresh');
//Route::get('me', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'me']);


// Авторизация
Route::post('login', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'login']);
Route::post('registration', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'registration']);
Route::post('refresh', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'refresh']);

//
Route::group(['middleware' => 'jwt.auth:api_v1'], function (){

    Route::post('logout', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'logout']);

    // Профиль
    Route::group(['prefix' => 'user'], function (){
        Route::get('/profile', [\App\Http\Controllers\Api\V1\User\ProfileController::class, 'index']);
        Route::put('/profile/update', [\App\Http\Controllers\Api\V1\User\ProfileController::class, 'update']);
        Route::delete('/profile/remove-avatar', [\App\Http\Controllers\Api\V1\User\ProfileController::class, 'removeAvatar']);
    });

    // Пользователи
    Route::group(['prefix' => 'users'], function (){
        Route::get('/', [\App\Http\Controllers\Api\V1\User\UserController::class, 'index']);
        Route::get('search/{user}', [\App\Http\Controllers\Api\V1\User\UserController::class, 'search']);
    });

    // Чат
    Route::group(['prefix' => 'chat'], function (){
        Route::get('/', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'show']);
        Route::post('/create', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'store']);
        Route::post('/join', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'join']);
        Route::post('/lave', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'lave']);
        Route::post('/delete', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'delete']);

        // Settings
        Route::get('/{chatId}/settings', [\App\Http\Controllers\Api\V1\Chat\ChatUserSettingsController::class, 'settingsByUser']);
        Route::post('/{chatId}/setting-change', [\App\Http\Controllers\Api\V1\Chat\ChatUserSettingsController::class, 'settingChangeByUser']);

        // Messages
        Route::get('{id}/messages', [\App\Http\Controllers\Api\V1\Chat\MessageController::class, 'messagesByChatId']);
        Route::post('messages/send', [\App\Http\Controllers\Api\V1\Chat\MessageController::class, 'store']);
        Route::post('messages/read', [\App\Http\Controllers\Api\V1\Chat\MessageController::class, 'read']);

    });
});


