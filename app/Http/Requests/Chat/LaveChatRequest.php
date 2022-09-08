<?php

namespace App\Http\Requests\Chat;

use App\Rules\IsNotUserToChat;
use App\Rules\IsUserCreatorAndUsersInChat;
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
            'id' => [
                'required',
                'exists:chat_rooms,id',
                // Если пользователь создал чат и в чете есть другие пользователи, то пользователь не может покинуть чат
                new IsUserCreatorAndUsersInChat(),
            ],
            'user_id' => [
                'required',
                new IsNotUserToChat($this->id) // Если пользователь не состоит в чате
            ],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Неверный чат.',
            'id.exists' => 'Неверный чат.',
        ];
    }

    /**
     * Обработка данных запроса перед валидацией
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id(),
        ]);
    }
}
