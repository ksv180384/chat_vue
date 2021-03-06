<?php

namespace App\Services;

use App\Models\Chat\ChatMessage;

class ChatMessageService extends Service{

    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new ChatMessage();
    }

    public function messagesByChatId($chatId)
    {
        $messages = $this->model
            ->list()
            ->where('chat_room_id', $chatId)
            ->orderByDesc('created_at')
            ->simplePaginate(20);

        return $messages;
    }

    /**
     *
     * @param $message
     * @return mixed
     */
    public function create($message)
    {
        $message = $this->model->create($message);
        $message = ChatMessage::list()->find($message->id);

        return $message;
    }
}
