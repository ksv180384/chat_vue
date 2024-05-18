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
     * @param $chatId
     * @return mixed
     * @throws \Exception
     */
    public function messagesByChatId($chatId)
    {
        try{
            $messages = $this->model->query()
                ->list()
                ->where('chat_room_id', $chatId)
                ->orderByDesc('created_at')
                ->simplePaginate(self::PAGINATE_COUNT);

            return $messages;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_data'));
        }
    }

    /**
     * Добавляем новое сообщение
     * @param $messageData
     * @return mixed
     * @throws \Exception
     */
    public function create($messageData)
    {
        try{
            $message = $this->model->query()->create($messageData);
            $message = ChatMessage::query()->list()->find($message->id);

            return $message;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_data'));
        }
    }

    /**
     * Фиксируем прочинанные сообщения чата
     * @param int $userId
     * @param int $chatId
     */
    public function read($userId, $chatId)
    {
        try{
            ChatRoomToUser::query()
                ->where('user_id', $userId)
                ->where('chat_room_id', $chatId)
                ->updateTimestamp();
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.update_data'));
        }
    }
}
