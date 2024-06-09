<?php

namespace App\Http\Middleware;

use App\Http\Resources\User\UserAuthResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageMiddleware
{
    /**
     * Добавляем дополнительные данные для запросов, которые загружают начальные данные страницы
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if(!Str::contains($request->getRequestUri(), 'api/v1/page')){
            return $response;
        }

        $arrResponse = json_decode($response->content(), true); // Получаем массив текущего ответа
        $user = Auth::check() ? UserAuthResource::make(Auth::user()) : null;

        $newResponse = [
            'data' => $arrResponse,
            'pages_info' => [
                'auth_data' => $user,
            ]
        ];
        $response->setContent(json_encode($newResponse)); // Добавляем измененый ответ

        return $response;
    }
}
