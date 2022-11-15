<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Chat\DeleteChatRequest;
use App\Http\Requests\Chat\LaveChatRequest;
use App\Http\Requests\Chat\JoinUserChatRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessagesPaginateResource;
use App\Models\Chat\ChatRoom;
use App\Http\Requests\Chat\CreateChatRequest;
use App\Services\ChatMessageService;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;

class ChatController extends BaseController
{
    /**
     * @var ChatService
     */
    private $chatService;

    /**
     * @var ChatMessageService
     */
    private $chatMessageService;

    public function __construct(
        ChatService $chatService,
        ChatMessageService $chatMessageService
    )
    {
        parent::__construct();

        $this->chatService = $chatService;
        $this->chatMessageService = $chatMessageService;
    }

    /**
     * Список чатов пользователя
     * @return ChatCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userId = Auth::id();
        try {
            $chats = $this->chatService->getByUserId($userId);
            return new ChatCollection($chats);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Получить данные чата
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            $chat = $this->chatService->getById($id);
            $messages = $this->chatMessageService->messagesByChatId($id);

            return response()->json([
                'chat' => new ChatResource($chat),
                'messages' => new MessagesPaginateResource($messages),
            ]);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Создать чат
     * @param CreateChatRequest $request
     * @return ChatResource|\Illuminate\Http\JsonResponse
     */
    public function store(CreateChatRequest $request)
    {
        try {
            $chat = $this->chatService->create($request->all());
            return new ChatResource($chat);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Добавить пользователя в чат
     * @param JoinUserChatRequest $request
     * @return ChatResource|\Illuminate\Http\JsonResponse
     */
    public function join(JoinUserChatRequest $request)
    {
        try {
            $chatRoom = ChatRoom::findOrFail($request->chat_room_id);
            $chatRoom->users()->attach($request->user_id);
            $chatRoom->load('users', 'settings');
            return new ChatResource($chatRoom);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Покинуть чат
     * @param LaveChatRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lave(LaveChatRequest $request)
    {
        ['id' => $chat_room_id, 'user_id' => $user_id] = $request->validated();

        try {
            $this->chatService->lave($chat_room_id, $user_id);
            return response()->json(['message' => 'Вы учпешно покинули чат.']);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Удвляем чат
     * @param DeleteChatRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteChatRequest $request)
    {
        try {
            $this->chatService->delete($request->id);
            return response()->json(['message' => 'Вы учпешно удалили чат.']);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
