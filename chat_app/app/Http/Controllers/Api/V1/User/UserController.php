<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends BaseController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * @return \Exception|\Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index()
    {
        try {
            return UserResource::collection(User::all());
        } catch (\Exception $e){
            return new \Exception(config('app_messages.errors.update_data'));
        }
    }

    /**
     * Поиск пользователя
     * @param $userName
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function search($userName)
    {
        try {
            $user = $this->userService->search($userName);
            return UserResource::collection($user);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
