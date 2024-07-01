<?php

namespace App\Services;

use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\User\ChatUsersResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SocketServer
{
    private $serverDomain  = 'chat-vue-nodejs:3077/';

    public function createChat(ChatResource $chat)
    {
        if (!Auth::check()){
            return false;
        }

        $res = $this->post('create-chat', Auth::id(), ['chat' => $chat]);
    }

    public function removeChat(int $chatId)
    {
        if (!Auth::check()){
            return false;
        }

        $res = $this->post('remove-chat', Auth::id(), ['chat_id' => $chatId]);
    }

    /**
     * @param array $joinData ['user', 'chat']
     * @return false|void
     */
    public function userJoinToChat(ChatUsersResource $joinUser, ChatResource $chat)
    {
        if (!Auth::check()){
            return false;
        }

        $res = $this->post('user-join-to-chat', Auth::id(), ['join_user' => $joinUser->toArray(), 'chat' => $chat->toArray()]);
    }

    public function userLaveChat(int $chatId, int $userId)
    {
        $res = $this->post('user-lave-chat', Auth::id(), ['chat_id' => $chatId, 'user_id' => $userId]);
    }

    /**
     * @param MessageResource $message
     * @return bool
     */
    public function sendMessage(MessageResource $message): bool
    {
        if (!Auth::check()){
            return false;
        }

        $res = $this->post('send-message', Auth::id(), $message->toArray());

        return true;
    }

    /**
     * @param int $chatId
     * @return bool
     */
    public function enterRoom(int $chatId)
    {
        if (!Auth::check()){
            return false;
        }

        $res = $this->post('enter-room', Auth::id(), ['chat_id' => $chatId]);

        return true;
    }

    /**
     * @param string $path
     * @param int $userId
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    private function post(string $path, int $userId, array $data)
    {
        $res = Http::post($this->serverDomain . $path, [
            'user_id' => $userId,
            'data' => $data,
        ]);

        return $res;
    }
}
