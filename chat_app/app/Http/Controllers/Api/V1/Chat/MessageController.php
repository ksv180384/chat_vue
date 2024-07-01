<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Chat\CreateMessageRequest;
use App\Http\Requests\Chat\ReadMessagesChat;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\Message\MessagesPaginateResource;
use App\Http\Resources\PaginationSimpleResource;
use App\Services\ChatMessageService;
use App\Services\SocketServer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MessageController extends BaseController
{

    public function __construct(
        private ChatMessageService $chatMessageService
    )
    {
        parent::__construct();
    }

    /**
     * Получаем сообщения чата
     * @param int $id
     * @return JsonResponse
     */
    public function messagesByChatId(int $id): JsonResponse
    {
        try{
            $messages = $this->chatMessageService->messagesByChatId($id);

            return response()->json([
                'messages' => MessageResource::collection(array_reverse($messages->items())),
                'pagination' => PaginationSimpleResource::make($messages),

            ]);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Добавляем сообщение в чат
     * @param CreateMessageRequest $request
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function store(CreateMessageRequest $request, SocketServer $socketServer): JsonResponse
    {
        try {
            $message = $this->chatMessageService->create($request->validated());
            $socketServer->sendMessage(MessageResource::make($message));
            return response()->json(['message' => 'Сообщение успешно отправлено.']);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Фиксрем дату прочтения пользователем сообщений чата
     * @param ReadMessagesChat $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @param ReadMessagesChat $request
     * @return JsonResponse|void
     */
    public function read(ReadMessagesChat $request)
    {
        try {
            $this->chatMessageService->read($request->user_id, $request->chat_room_id);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
