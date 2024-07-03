<?php

namespace App\Services;

use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoomToUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use phpDocumentor\Reflection\Types\Self_;

class ChatMessageService extends Service
{

    const PAGINATE_COUNT = 10;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Получаем сообщения чата
     * @param $chatId
     * @return Paginator
     * @throws \Exception
     */
    public function messagesByChatId(int $chatId): Paginator
    {
        try{
            $messages = ChatMessage::query()
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
     * @param array $messageData
     * @return Model
     * @throws \Exception
     */
    public function create(array $messageData): Model
    {
        try{
            $message = ChatMessage::query()->create($messageData);

            return $message;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_data'));
        }
    }

    /**
     * Фиксируем прочинанные сообщения чата
     * @param int $userId
     * @param int $chatId
     * @return void
     * @throws \Exception
     */
    public function read(int $userId, int $chatId): void
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
