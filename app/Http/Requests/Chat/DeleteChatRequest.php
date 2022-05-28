<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat\ChatRoom;
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
            'id' => 'required',
            'creator_id' => 'required',
            'creator' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'неверный чат.',
            'creator_id.required' => 'Неверный пользователь.',
            'creator.required' => 'У вас недостаточно прав для удаления.',
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

        $chatRoomToUser = ChatRoom::where('id', $id)->where('creator_id', $userId)->exists();

        $this->merge([
            'creator_id' => $userId,
            // Является ли пользователь создателем чата
            'creator' => $chatRoomToUser ? 'ok' : null,
        ]);
    }
}
