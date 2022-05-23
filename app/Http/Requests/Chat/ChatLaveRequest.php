<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat\ChatRoomToUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChatLaveRequest extends FormRequest
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
            'user_to_chat' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_to_chat.required' => 'Вы не сосотоите в чате.',
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

        $chatRoomToUser = ChatRoomToUser::where('user_id', $userId)->where('chat_room_id', $id)->first();

        $this->merge([
            'user_id' => $userId,
            'user_to_chat' => $chatRoomToUser ? 'user_in_chat' : null,
        ]);
    }
}
