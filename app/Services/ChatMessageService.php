<?php

namespace App\Services;

use App\Models\Chat\ChatMessage;

class ChatMessageService extends Service{

    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new ChatMessage();
    }

}
