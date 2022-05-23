<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Chat\CreateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat\ChatMessage;
use App\Services\ChatMessageService;

class MessageController extends BaseController
{

    /**
     * @var ChatMessageService
     */
    private $chatMessageService;

    public function __construct(
        ChatMessageService $chatMessageService
    )
    {
        parent::__construct();

        $this->chatMessageService = $chatMessageService;
    }

    public function store(CreateMessageRequest $request)
    {
        $message = $this->chatMessageService->create($request->validated());

        return new MessageResource($message);
    }
}
