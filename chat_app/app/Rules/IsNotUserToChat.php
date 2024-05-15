<?php

namespace App\Rules;

use App\Models\Chat\ChatRoomToUser;
use Illuminate\Contracts\Validation\Rule;

/**
 * Если пользователь не состоит в чате
 */
class IsNotUserToChat implements Rule
{

    protected $chatRoomId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($chatRoomId)
    {
        $this->chatRoomId = $chatRoomId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $isUserAlready = ChatRoomToUser::where('user_id', $value)
            ->where('chat_room_id', $this->chatRoomId)
            ->exists();

        return $isUserAlready;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Вы не состоите в текущем чате.';
    }
}
