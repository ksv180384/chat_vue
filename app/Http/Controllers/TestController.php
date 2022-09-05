<?php

namespace App\Http\Controllers;

use App\Models\Chat\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $chats = ChatRoom::with(['users' => function($q){
            return $q->where('chat_room_to_users.user_id', 1);
        }])->get();

        foreach ($chats as $chat){
            echo '<h3>' . $chat->title . '</h3>';
            dump($chat->users);
        }

        return view('test');
    }
}
