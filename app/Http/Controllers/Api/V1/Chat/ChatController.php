<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoom;
use App\Http\Requests\Chat\CreateChatRequest;
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

    public function __construct(
        ChatService $chatService
    )
    {
        parent::__construct();

        $this->chatService = $chatService;
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

        $messages = ChatMessage::list()
            ->where('chat_room_id', $chat->id)
            ->orderBy('created_at')
            ->simplePaginate(20);

        return response()->json([
            'chat' => new ChatResource($chat),
            'messages' => new MessageResource($messages),
        ]);
    }

    public function store(CreateChatRequest $request)
    {
        $chat = $this->chatService->create($request->all());//ChatRoom::create($request->all());

        return new ChatResource($chat);
    }

    public function join(Request $request)
    {
        $chatRoom = ChatRoom::findOrFail($request->chat_id);

        $chatRoom->users()->attach($request->user_id);
        $chatRoom->load('users');

        return new ChatResource($chatRoom);
    }
}
