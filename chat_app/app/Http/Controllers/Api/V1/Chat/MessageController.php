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

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Получаем сообщения чата
     * @param int $id
     * @param ChatMessageService $chatMessageService
     * @return JsonResponse
     */
    public function messagesByChatId(int $id, ChatMessageService $chatMessageService): JsonResponse
    {
        try{
            $messages = $chatMessageService->messagesByChatId($id);

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
     * @param ChatMessageService $chatMessageService
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function store(
        CreateMessageRequest $request,
        ChatMessageService $chatMessageService,
        SocketServer $socketServer
    ): JsonResponse
    {
        try {
            $message = $chatMessageService->create($request->validated());
            $socketServer->sendMessage(MessageResource::make($message));
            return response()->json(['message' => 'Сообщение успешно отправлено.']);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Фиксрем дату прочтения пользователем сообщений чата
     * @param ReadMessagesChat $request
     * @param ChatMessageService $chatMessageService
     * @return JsonResponse|void
     */
    public function read(ReadMessagesChat $request, ChatMessageService $chatMessageService)
    {
        try {
            $chatMessageService->read($request->user_id, $request->chat_room_id);

            return response()->json(['message' => 'Зарос успешно выполнен.']);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
