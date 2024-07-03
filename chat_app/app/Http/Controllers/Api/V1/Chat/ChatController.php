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
use App\Http\Resources\User\UserResource;
use App\Models\Chat\ChatRoom;
use App\Models\User;
use App\Services\ChatMessageService;
use App\Services\ChatService;
use App\Services\SocketServer;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ChatController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Список чатов пользователя
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
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
                'messages' => MessageResource::collection(array_reverse($messages->items())),
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
     * @param ChatService $chatService
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function store(
        CreateChatRequest $request,
        ChatService $chatService,
        SocketServer $socketServer
    ): JsonResponse
    {
        try {
            $chatRoom = $chatService->create($request->validated());
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
    public function join(
        JoinUserChatRequest $request,
        SocketServer $socketServer
    ): JsonResponse
    {
        try {
            $chatRoom = ChatRoom::findOrFail($request->chat_room_id);
            $joinUser = User::query()->findOrFail($request->user_id);
            $chatRoom->users()->attach($request->user_id);

            $resChatRoom = ChatRoom::query()
                ->with(['users:id,name', 'creator'])
                ->settingByUserId($request->user_id)
                ->where('id', $request->chat_room_id)
                ->first();

            $socketServer->userJoinToChat(ChatUsersResource::make($joinUser), ChatResource::make($resChatRoom));

            return response()->json(['message' => 'Пользователь успешно добавлен к чату.']);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Покинуть чат
     * @param LaveChatRequest $request
     * @param ChatService $chatService
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function lave(
        LaveChatRequest $request,
        ChatService $chatService,
        SocketServer $socketServer
    ): JsonResponse
    {
        ['id' => $chatRoomId, 'user_id' => $userId] = $request->validated();

        try {
            $chatService->lave($chatRoomId, $userId);
            $socketServer->userLaveChat($chatRoomId, $userId);
            return response()->json(['message' => 'Вы учпешно покинули чат.']);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Удвляем чат
     * @param DeleteChatRequest $request
     * @param ChatService $chatService
     * @param SocketServer $socketServer
     * @return JsonResponse
     */
    public function delete(
        DeleteChatRequest $request,
        ChatService $chatService,
        SocketServer $socketServer
    ): JsonResponse
    {
        try {
            $chatService->delete($request->id);
            $socketServer->removeChat($request->id);
            return response()->json(['message' => 'Вы учпешно удалили чат.']);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
