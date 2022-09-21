<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Профиль пользователя
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $user = $this->userService->getById(Auth::id());
            return new UserResource($user);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Обновляем данные пользователя
     * @param UpdateUserRequest $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request)
    {
        try{
            $user = $this->userService->update(Auth::user(), $request);
            return new UserResource($user);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Удаляем аватар
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function removeAvatar()
    {
        try{
            $user = $this->userService->removeAvatar(Auth::user());
            return new UserResource($user);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
