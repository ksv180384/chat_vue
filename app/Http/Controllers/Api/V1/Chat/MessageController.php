<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\CreateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoom;
use Illuminate\Http\Request;

class MessageController extends BaseController
{
    public function store(CreateMessageRequest $request)
    {
        $message = ChatMessage::create($request->validated());
        $message = ChatMessage::list()->find($message->id);

        return new MessageResource($message);
    }
}
