<?php

namespace App\Services;

use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatRoomToUser;
use phpDocumentor\Reflection\Types\Self_;

class ChatMessageService extends Service{

    const PAGINATE_COUNT = 10;

    public function __construct()
    {
        parent::__construct();
        $this->model = new ChatMessage();
    }

    /**
     * Получаем сообщения чата
     * @param int $chatId
     * @return mixed
     */
    public function messagesByChatId($chatId)
    {
        $messages = $this->model
            ->list()
            ->where('chat_room_id', $chatId)
            ->orderByDesc('created_at')
            ->simplePaginate(self::PAGINATE_COUNT);

        return $messages;
    }

    /**
     * Добавляем новое сообщение
     * @param array $messageData
     * @return mixed
     */
    public function create($messageData)
    {
        $message = $this->model->create($messageData);
        $message = ChatMessage::list()->find($message->id);

        return $message;
    }

    /**
     * Фиксируем прочинанные сообщения чата
     * @param int $userId
     * @param int $chatId
     */
    public function read($userId, $chatId)
    {
        ChatRoomToUser::where('user_id', $userId)->where('chat_room_id', $chatId)->updateTimestamp();
    }
}
