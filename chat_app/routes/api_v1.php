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
Route::post('login', [\App\Http\Controllers\Api\V1\Auth\AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('registration', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'registration']);
Route::post('refresh', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'refresh']);

//
Route::group(['middleware' => ['auth:api_v1', 'page-data']], function () {

    Route::post('logout', [\App\Http\Controllers\Api\V1\Auth\AuthenticatedSessionController::class, 'destroy']);

    // Профиль
    Route::get('page/profile', [\App\Http\Controllers\Api\V1\User\ProfileController::class, 'index']);
    Route::put('user/profile/update', [\App\Http\Controllers\Api\V1\User\ProfileController::class, 'update']);
    Route::delete('user/profile/remove-avatar', [\App\Http\Controllers\Api\V1\User\ProfileController::class, 'removeAvatar']);

    // Пользователи

    Route::get('users/', [\App\Http\Controllers\Api\V1\User\UserController::class, 'index']);
    Route::get('users/search/{user}', [\App\Http\Controllers\Api\V1\User\UserController::class, 'search']);

    // Чат user-chats
    Route::get('page/', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'index']);
    Route::get('chat/user-chats', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'getUserChats']);
    Route::get('page/chat/{id}', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'show']);
    Route::post('chat/create', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'store']);
    Route::post('chat/join', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'join']);
    Route::post('chat/lave', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'lave']);
    Route::post('chat/delete', [\App\Http\Controllers\Api\V1\Chat\ChatController::class, 'delete']);

    // Settings
    Route::get('page/chat/{chatId}/settings', [\App\Http\Controllers\Api\V1\Chat\ChatUserSettingsController::class, 'settingsByUser']);
    Route::post('chat/{chatId}/setting-change', [\App\Http\Controllers\Api\V1\Chat\ChatUserSettingsController::class, 'settingChangeByUser']);

    // Messages
    Route::get('chat/{id}/messages', [\App\Http\Controllers\Api\V1\Chat\MessageController::class, 'messagesByChatId']);
    Route::post('chat/messages/send', [\App\Http\Controllers\Api\V1\Chat\MessageController::class, 'store']);
    Route::post('chat/messages/read', [\App\Http\Controllers\Api\V1\Chat\MessageController::class, 'read']);
});


