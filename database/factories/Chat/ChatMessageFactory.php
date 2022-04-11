<?php

namespace Database\Factories\Chat;

use App\Models\Chat\ChatMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'chat_room_id' => $user->chatToUser->random()->chat_room_id,
            'message' => $this->faker->text(100),
        ];
    }
}
