<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Все пользователи
     * @return UserCollection|\Exception
     */
    public function index()
    {
        try {
            return new UserCollection(User::all());
        } catch (\Exception $e){
            return new \Exception(config('app_messages.errors.update_data'));
        }
    }

    /**
     * Поиск пользователя
     * @param $userName
     * @return UserCollection|\Illuminate\Http\JsonResponse
     */
    public function search($userName)
    {
        try {
            $user = $this->userService->search($userName);
            return new UserCollection($user);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
