<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\JoinUserChatRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoom;
use App\Http\Requests\Chat\CreateChatRequest;
use App\Services\ChatMessageService;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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

    public function index()
    {
        $userId = Auth::id();
        $chats = $this->chatService->getByUserId($userId);

        return new ChatCollection($chats);
    }

    public function show($id)
    {
        $chat = $this->chatService->getById($id);
        $messages = $this->chatMessageService->messagesByChatId($id);

        return response()->json([
            'chat' => new ChatResource($chat),
            'messages' => new MessageResource($messages),
        ]);
    }

    public function store(CreateChatRequest $request)
    {
        $chat = $this->chatService->create($request->all());

        return new ChatResource($chat);
    }

    public function join(JoinUserChatRequest $request)
    {
        $chatRoom = ChatRoom::findOrFail($request->chat_id);

        $chatRoom->users()->attach($request->user_id);
        $chatRoom->load('users');

        return new ChatResource($chatRoom);
    }
}
