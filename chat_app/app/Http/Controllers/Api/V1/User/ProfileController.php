<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Профиль пользователя
     * @param UserService $userService
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserService $userService): JsonResponse
    {
        try{
            $user = $userService->getById(Auth::id());

            return response()->json(new UserResource($user));
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Обновляем данные пользователя
     * @param UpdateUserRequest $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, UserService $userService): JsonResponse
    {
        try{
            $currentUser = Auth::user();
            $user = $userService->update($currentUser, $request);
            return response()->json(new UserResource($user));
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Удаляем аватар
     * @param UserService $userService
     * @return UserResource|JsonResponse
     */
    public function removeAvatar(UserService $userService)
    {
        try{
            $currentUser = Auth::user();
            $user = $userService->removeAvatar($currentUser);
            return response()->json(new UserResource($user));
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
