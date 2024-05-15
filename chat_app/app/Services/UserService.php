<?php

namespace App\Services;

use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }

    /**
     * Поиск пользователя
     * @param string $userName
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function search($userName)
    {
        try{
            $users = $this->model->query()
                ->where('name', 'LIKE', $userName . '%')
                ->orderBy('name')
                ->get();
            return $users;
        }catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_data'));
        }
    }

    /**
     * Получаем пользователя
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function getById($id)
    {
        try{
            $user = $this->model->query()->find($id);
            return $user;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_data'));
        }
    }

    /**
     * Обновляем данные пользователя
     * @param User $user
     * @param UpdateUserRequest $request
     * @return User
     * @throws \Exception
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        try {
            $user->update([
                'name' => $request->name,
            ]);

            $file = $request->file('avatar');
            if($file){
                $this->updateAvatar($user, $file);
            }

            return $user;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.add_data'));
        }
    }

    /**
     * Удаляем аватар пользователя
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function removeAvatar(User $user)
    {
        try {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);

            return $user;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.delete_data'));
        }
    }

    private function updateAvatar(User $user, UploadedFile $file)
    {
        $this->removeAvatar($user);
        $path = 'users/' . $user->id;
        $pathFile = (new UploadFile())->upload($file, $path);

        $user->update([
            'avatar' => $pathFile,
        ]);
    }
}
