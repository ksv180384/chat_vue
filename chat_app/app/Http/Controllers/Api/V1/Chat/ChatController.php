<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Chat\CreateChatRequest;
use App\Http\Requests\Chat\DeleteChatRequest;
use App\Http\Requests\Chat\JoinUserChatRequest;
use App\Http\Requests\Chat\LaveChatRequest;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\Chat\ChatShowResource;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\PaginationSimpleResource;
use App\Http\Resources\User\ChatUsersResource;
use App\Models\Chat\ChatRoom;
use App\Services\ChatMessageService;
use App\Services\ChatService;
use App\Services\SocketServer;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ChatController extends BaseController
{

    public function __construct(
        private ChatService $chatService,
        private ChatMessageService $chatMessageService
    )
    {
        parent::__construct();
    }

    /**
     * Список чатов пользователя
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /*
        $userId = Auth::id();
        try {
            $chats = $this->chatService->getByUserId($userId);
            return response()->json(ChatResource::collection($chats));
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
        */
        return response()->json([]);
    }

    /**
     * Получаем чаты в которых состоит автоизованный пользователь
     * @param ChatService $chatService
     * @return JsonResponse
     */
    public function getUserChats(ChatService $chatService): JsonResponse
    {
        try {
            $chats = $chatService->getByUserId(Auth::id());
            return response()->json(ChatResource::collection($chats));
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Получить данные чата
     * @param int $id
     * @param ChatService $chatService
     * @param ChatMessageService $chatMessageService
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function show(
        int $id,
        ChatService $chatService,
        ChatMessageService $chatMessageService,
        UserService $userService,
        SocketServer $socketServer
    ): JsonResponse
    {
        try{
            $chat = $chatService->getById($id);
            $chatUsers = $userService->getUsersById($id);
            $messages = $chatMessageService->messagesByChatId($id);

            $socketServer->enterRoom($id);

            return response()->json([
                'chat' => ChatShowResource::make($chat),
                'users' => ChatUsersResource::collection($chatUsers),
                'messages' => MessageResource::collection($messages->items()),
                'pagination' => PaginationSimpleResource::make($messages),
            ]);
        }catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Создать чат
     * @param CreateChatRequest $request
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function store(CreateChatRequest $request, SocketServer $socketServer): JsonResponse
    {
        try {
            $chatRoom = $this->chatService->create($request->validated());
            $socketServer->createChat(ChatResource::make($chatRoom));
            return response()->json(ChatResource::make($chatRoom));
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Добавить пользователя в чат
     * @param JoinUserChatRequest $request
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function join(JoinUserChatRequest $request, SocketServer $socketServer): JsonResponse
    {
        try {
            $chatRoom = ChatRoom::findOrFail($request->chat_room_id);
            $chatRoom->users()->attach($request->user_id);
            $chatRoom->load('users:id,name', 'settings');

            $socketServer->userJoinToChat(ChatResource::make($chatRoom));

            return response()->json(['message' => 'Сообщение успешно отправлено.']);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Покинуть чат
     * @param LaveChatRequest $request
     * @return JsonResponse
     */
    public function lave(LaveChatRequest $request): JsonResponse
    {
        ['id' => $chatRoomId, 'user_id' => $userId] = $request->validated();

        try {
            $this->chatService->lave($chatRoomId, $userId);
            return response()->json(['message' => 'Вы учпешно покинули чат.']);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Удвляем чат
     * @param DeleteChatRequest $request
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function delete(DeleteChatRequest $request, SocketServer $socketServer): JsonResponse
    {
        try {
            $this->chatService->delete($request->id);
            $socketServer->removeChat($request->id);
            return response()->json(['message' => 'Вы учпешно удалили чат.']);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
