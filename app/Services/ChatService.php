<?php

namespace App\Services;

use App\Models\Chat\ChatUserSettings;
use App\Models\Chat\ChatRoom;
use Illuminate\Support\Facades\Auth;

class ChatService extends Service{

    public function __construct()
    {
        parent::__construct();
        $this->model = new ChatRoom();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id){
        $chat = $this->model->one()->find($id);

        return $chat;
    }

    /**
     * Добавляем новый чат
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $chatRoom = ChatRoom::create($data);
        $chatRoom->users()->attach(Auth::id());
        return $chatRoom;
    }

    /**
     * Получаем чаты в которых состоит пользователь
     * @param $userId
     * @return mixed
     */
    public function getByUserId($userId){
        $chats = $this->model
            ->select('chat_rooms.*')
            ->with(['settings'])
            ->list()
            ->leftJoin('chat_room_to_users', 'chat_rooms.id', '=', 'chat_room_to_users.chat_room_id')
            ->orWhere('chat_room_to_users.user_id', $userId)
            ->get();

        return $chats;
    }

    /**
     * Удаляем связь пользователя с чатом
     * @param $chatId
     * @param $userId
     */
    public function lave($chatId, $userId)
    {
        $chatRoom = ChatRoom::findOrFail($chatId);
        $chatRoom->users()->detach($userId);

        if($chatRoom->users()->count() == 0){
            $chatRoom->delete();
        }
    }

    /**
     * Удаляем чат
     * @param $chatId
     */
    public function delete($chatId)
    {
        $chatRoom = ChatRoom::findOrFail($chatId);
        $chatRoom->users()->detach();
        $chatRoom->messages()->delete();

        $chatRoom->delete();
    }
}
