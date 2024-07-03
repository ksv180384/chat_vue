<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return JsonResponse
     */
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();

            return response()->json(UserResource::collection($users));
        } catch (\Exception $e){
            response()->json(['message' => config('app_messages.errors.update_data')], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Поиск пользователя
     * @param string $userName
     * @param UserService $userService
     * @return JsonResponse
     */
    public function search(string $userName, UserService $userService)
    {
        try {
            $user = $userService->search($userName);

            return response()->json(UserResource::collection($user));
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
