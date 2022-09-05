<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Chat\CreateMessageRequest;
use App\Http\Requests\Chat\ReadMessagesChat;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessagesCollection;
use App\Http\Resources\MessagesPaginateResource;
use App\Services\ChatMessageService;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageController extends BaseController
{

    /**
     * @var ChatMessageService
     */
    private $chatMessageService;

    /**
     * MessageController constructor.
     * @param ChatMessageService $chatMessageService
     */
    public function __construct(
        ChatMessageService $chatMessageService
    )
    {
        parent::__construct();

        $this->chatMessageService = $chatMessageService;
    }

    /**
     * Получаем сообщения чата
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function messagesByChatId($id)
    {
        $messages = $this->chatMessageService->messagesByChatId($id);
        return response()->json(new MessagesPaginateResource($messages));
    }

    /**
     * Добавляем сообщение в чат
     * @param CreateMessageRequest $request
     * @return MessageResource
     */
    public function store(CreateMessageRequest $request)
    {
        $message = $this->chatMessageService->create($request->validated());

        return new MessageResource($message);
    }

    /**
     * Фиксрем дату прочтения пользователем сообщений чата
     * @param ReadMessagesChat $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(ReadMessagesChat $request)
    {
        try {
            $this->chatMessageService->read($request->user_id, $request->chat_room_id);
        }catch (\Exception $e){
            return response()->json(['message' => 'Ошибка'], 404);
        }
    }
}
