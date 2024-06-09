<?php

namespace App\Services;

use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserService extends Service
{
    const PATH_STORAGE = 'users';

    public function __construct()
    {
        parent::__construct();
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
            $users = User::query()
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
            $user = User::query()->find($id);
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
    public function removeAvatar(User $user): User
    {
        try {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);

            return $user;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.delete_data'));
        }
    }

    /**
     * @param int $chatId
     * @return Collection
     */
    public function getUsersById(int $chatId): Collection
    {
        $users = User::query()
            ->select([
                'users.id',
                'users.name',
                'users.avatar',
            ])
            ->whereHas('chats', function (Builder $query) use ($chatId) {
                $query->where('chat_rooms.id', $chatId);
            })
            ->get();

        return $users;
    }

    private function updateAvatar(User $user, UploadedFile $file)
    {
        $this->removeAvatar($user);
        $path = self::PATH_STORAGE . '/' . $user->id;
        $pathFile = (new UploadFile())->upload($file, $path);

        $user->update([
            'avatar' => $pathFile,
        ]);
    }
}
