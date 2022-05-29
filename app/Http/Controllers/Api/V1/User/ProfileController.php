<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
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
     * @return UserResource
     */
    public function index()
    {
        $user = $this->userService->getById(Auth::id());
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $this->userService->update(Auth::user(), $request);

        return new UserResource($user);
    }
}
