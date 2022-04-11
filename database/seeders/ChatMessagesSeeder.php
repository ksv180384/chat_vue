<?php

namespace Database\Seeders;

use App\Models\Chat\ChatMessage;
use Illuminate\Database\Seeder;

class ChatMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChatMessage::factory()
            ->count(350)
            ->create();
    }
}
