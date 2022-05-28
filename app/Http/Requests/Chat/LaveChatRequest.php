<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatRoomToUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LaveChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'user_id' => 'required',
            // Состоит ли пользователь в чате
            'user_to_chat' => 'required',
            // Если пользователь создал чат и в чете есть другие пользователи, то пользователь не может покинуть чат
            'creator_only_creator' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_to_chat.required' => 'Вы не сосотоите в чате.',
            'creator_only_creator.required' => 'Вы не можете покинуть чат пока в нем есть пользователи.',
        ];
    }

    /**
     * Обработка данных запроса перед валидацией
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $data = $this->all();

        $id = $data['id'];
        $userId = Auth::id();

        $chatRoomToUser = ChatRoomToUser::where('user_id', $userId)->where('chat_room_id', $id)->exists();
        $chatRoom = ChatRoom::withCount(['users'])->find($id);

        $this->merge([
            'user_id' => $userId,
            // Состоит ли пользователь в чате
            'user_to_chat' => $chatRoomToUser ? 'user_in_chat' : null,
            // Если пользователь создал чат и в чете есть другие пользователи, то пользователь не может покинуть чат
            'creator_only_creator' => $chatRoom && $chatRoom->users_count > 1 && $chatRoom->creator_id == $userId ? null : 'only',
        ]);
    }
}
