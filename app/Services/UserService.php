<?php

namespace App\Services;

use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function getById($id)
    {
        $user = $this->model->find($id);
        return $user;
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $pathAvatar = null;
        $file = $request->file('avatar');
        if($file){
            $avatarName = 'avatar.' . $file->extension();
            $path = 'users/' . $user->id . '/' . $avatarName;
            Storage::disk('public')->put($path, file_get_contents($file));
        }

        $user->update([
            'name' => $request->name,
            'avatar' => $avatarName,
        ]);

        return $user;
    }
}
