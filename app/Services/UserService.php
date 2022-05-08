<?php

namespace App\Services;

use App\Models\User;

class UserService extends Service
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }

    public function search($userName)
    {
        $users = $this->model
            ->where('name', 'LIKE', $userName . '%')
            ->orderBy('name')
            ->get();

        return $users;
    }
}