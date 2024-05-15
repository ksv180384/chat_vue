<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatRoomToUser;
use App\Rules\IsCurrentUserCreatorChat;
use App\Rules\IsUserAlreadyToChat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class JoinUserChatRequest extends FormRequest
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
            'chat_room_id' => [
                'required',
                'exists:chat_rooms,id',
                new IsCurrentUserCreatorChat() // Проверка является текущий авторизованный пользователь создателем чата
            ],
            'user_id' => [
                'required',
                'exists:users,id',
                new IsUserAlreadyToChat($this->chat_room_id) // Проверка состоит ли уже пользователь в чате
            ],
        ];
    }

    public function messages()
    {
        return [
            'chat_room_id.required' => 'Неверно задан чат.',
            'exists.exists' => 'Неверно задан чат.',
            'user_id.exists' => 'Неверно задан пользователь.',
        ];
    }

    /**
     * Обработка данных перед проверкой
     * @return void
     */
    protected function prepareForValidation()
    {

    }

}
