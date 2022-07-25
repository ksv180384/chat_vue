<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat\ChatRoomToUser;
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
            'chat_room_id' => 'required|exists:chat_rooms,id',
            'not_user_already' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'not_user_already.required' => 'Пользователь уже состоит в этом чате.',
        ];
    }

    protected function prepareForValidation()
    {
        $data = $this->all();

        $userId = $data['user_id'];
        $roomId = $data['chat_room_id'];

        $isUserAlready = ChatRoomToUser::where('user_id', $userId)
            ->where('chat_room_id', $roomId)
            ->exists();

        $this->merge([
            'not_user_already' => $isUserAlready ? null : true,
        ]);
    }

}
