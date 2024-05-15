<?php

namespace Database\Seeders;

use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatRoomToUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatRoomToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user){
            $chatRoom = ChatRoom::inRandomOrder()->first();
            ChatRoomToUser::create([
                'user_id' => $user->id,
                'chat_room_id' => $chatRoom->id,
            ]);
        }
    }
}
