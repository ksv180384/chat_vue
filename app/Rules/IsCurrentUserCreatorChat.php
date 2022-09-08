<?php

namespace App\Rules;

use App\Models\Chat\ChatRoom;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * Проверка является текущий авторизованный пользователь создателем чата
 */
class IsCurrentUserCreatorChat implements Rule
{

    protected $data = [];

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
        $chatRoom = ChatRoom::find($value);
        return $chatRoom->creator_id == Auth::id();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'У вас недостаточно прав для добавления пользователя в чат.';
    }
}
