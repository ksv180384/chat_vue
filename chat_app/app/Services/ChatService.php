<?php

namespace App\Services;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Chat\ChatUserSettings;
use App\Models\Chat\ChatRoom;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatService
{

    public function __construct(){}

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function getById($id){
        $chat = ChatRoom::query()->one()->find($id);

        if(!$chat){
            throw new \Exception(config('app_messages.errors.get_data'));
        }

        return $chat;
    }

    /**
     * Добавляем новый чат
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function create($data)
    {
        $userId = Auth::id();

        try{
            $chatRoom = DB::transaction(function () use ($data, $userId) {
                $chatRoom = ChatRoom::query()->create($data);
                $chatRoom->users()->attach($userId);

                return $chatRoom;
            }, 3);
            (new ChatUserSettingsService())->initSettings($chatRoom->id, $userId);
            return $chatRoom;
        }catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_update'));
        }
    }

    /**
     * Получаем ид чатов в которых состоит пользователь
     * @return Collection
     * @throws \Exception
     */
    public function getIdsByUserId(int $id): Collection
    {
        $currentUser = User::with(['chats:id'])->find($id);

        if(!$currentUser->chats){
            throw new \Exception(config('app_messages.errors.get_data'));
        }

        return !empty($currentUser->chats) ? $currentUser->chats->pluck('id') : collect([]);
    }

    /**
     * Получаем чаты в которых состоит пользователь
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getByUserId($userId)
    {
        $chats = ChatRoom::query()
            ->select('chat_rooms.*')
            ->with(['settings'])
            ->list()
            ->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })
//            ->leftJoin('chat_room_to_users', 'chat_rooms.id', '=', 'chat_room_to_users.chat_room_id')
//            ->orWhere('chat_room_to_users.user_id', $userId)
            ->get();

        if(!$chats){
            throw new \Exception(config('app_messages.errors.get_data'));
        }

        return $chats;
    }

    /**
     * Удаляем связь пользователя с чатом
     * @param $chatId
     * @param $userId
     * @return void
     * @throws \Exception
     */
    public function lave($chatId, $userId)
    {

        try {
            DB::transaction(function () use ($chatId, $userId) {
                $chatRoom = ChatRoom::query()->findOrFail($chatId);
                $chatRoom->users()->detach($userId);
                $chatRoom->settings()->delete();

                if($chatRoom->users()->count() == 0){
                    $chatRoom->delete();
                }
            });
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.update_data'));
        }
    }

    /**
     * Удаляем чат
     * @param $chatId
     */
    public function delete($chatId)
    {
        try {
            DB::transaction(function () use ($chatId) {
                $chatRoom = ChatRoom::query()->findOrFail($chatId);
                $chatRoom->users()->detach();
                $chatRoom->settings()->delete();
                $chatRoom->messages()->delete();

                $chatRoom->delete();
            });
        }catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.update_data'));
        }
    }
}
