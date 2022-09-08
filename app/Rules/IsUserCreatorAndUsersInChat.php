<?php

namespace App\Rules;

use App\Models\Chat\ChatRoom;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * Проверка если пользователь создал чат и в чете есть другие пользователи, то пользователь не может покинуть чат
 */
class IsUserCreatorAndUsersInChat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $userId = Auth::check() ? Auth::id() : 0;
        $chatRoom = ChatRoom::withCount(['users'])->find($value);
        return !($chatRoom && $chatRoom->users_count > 1 && $chatRoom->creator_id == $userId);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Вы не можете покинуть чат пока в нем есть пользователи.';
    }
}
