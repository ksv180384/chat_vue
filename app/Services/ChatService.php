<?php

namespace App\Services;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Chat\ChatUserSettings;
use App\Models\Chat\ChatRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatService extends Service{

    public function __construct()
    {
        parent::__construct();
        $this->model = new ChatRoom();
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function getById($id){
        $chat = $this->model->query()->one()->find($id);

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
            $chatRoom = ChatRoom::query()->create($data);
            $chatRoom->users()->attach($userId);
            (new ChatUserSettingsService())->initSettings($chatRoom->id, $userId);
            return $chatRoom;
        }catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_update'));
        }
    }

    /**
     * Получаем чаты в которых состоит пользователь
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getByUserId($userId){
        $chats = $this->model->query()
            ->select('chat_rooms.*')
            ->with(['settings'])
            ->list()
            ->leftJoin('chat_room_to_users', 'chat_rooms.id', '=', 'chat_room_to_users.chat_room_id')
            ->orWhere('chat_room_to_users.user_id', $userId)
            ->get();

        if(!$chats){
            throw new \Exception(config('app_messages.errors.get_data'));
        }

        // Добавляем настройки чата пользователю если их нет
        foreach ($chats as $k => $chat){
            if(!$chat->settings){
                $chats[$k]->settings = (new ChatUserSettingsService())->initSettings($chat->id, $userId);
            }
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
