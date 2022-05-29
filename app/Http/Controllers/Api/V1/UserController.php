<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

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
     * @return UserCollection
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Профиль пользователя
     * @return UserResource
     */
    public function profile()
    {
        $user = $this->userService->getById(Auth::id());
        return new UserResource($user);
    }

    /**
     * Поис пользователя
     * @param $user
     * @return mixed
     */
    public function search($user)
    {
        $user = $this->userService->search($user);
        return new UserCollection($user);
    }
}
