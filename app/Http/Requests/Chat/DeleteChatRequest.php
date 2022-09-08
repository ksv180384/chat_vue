<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatUserSettings;
use App\Rules\IsCurrentUserCreatorChat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteChatRequest extends FormRequest
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
            'id' => [
                'required',
                'exists:chat_rooms,id',
                new IsCurrentUserCreatorChat() // Проверка является текущий авторизованный пользователь создателем чата
            ]
        ];

    }

    public function messages()
    {
        return [
            'id.required' => 'неверный чат.',
            'id.exists' => 'неверный чат.',
        ];
    }

    /**
     * Обработка данных запроса перед валидацией
     *
     * @return void
     */
    protected function prepareForValidation()
    {
    }
}
