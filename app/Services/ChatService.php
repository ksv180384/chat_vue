<?php

namespace App\Services;

use App\Models\Chat\ChatRoom;
use Illuminate\Support\Facades\Auth;

class ChatService extends Service{

    private $model;

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
            ->list()
            ->leftJoin('chat_room_to_users', 'chat_rooms.id', '=', 'chat_room_to_users.chat_room_id')
            ->orWhere('chat_room_to_users.user_id', $userId)
            ->get();

        return $chats;
    }

    public function lave($data)
    {
        $chatRoom = ChatRoom::findOrFail($data['id']);
        $chatRoom->users()->detach($data['user_id']);

        if($chatRoom->users()->count() == 0){
            $chatRoom->delete();
        }
    }
}
