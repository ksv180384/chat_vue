<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatRoomToUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChangeSettingRequest extends FormRequest
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
            'user_id' => 'required',
            'setting' => 'required',
            'value' => 'required',
            'is_user_to_chat' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'setting.required' => 'Неверная настройка.',
            'is_user_to_chat.required' => 'Вы не состоите в в чате в котором меняете настройки.',
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

        $userId = Auth::id();
        $chatId = $this->route('chatId');
        $setting = $data['setting'];
        $value = $data['value'] ? true : false;

        $isChatRoomToUser = ChatRoomToUser::where('user_id', $userId)->where('chat_room_id', $chatId)->exists();
        $setting = in_array($setting, ['show_notification_new_message']) ? $setting : null;

        $this->merge([
            'user_id' => $userId,
            // Состоит ли пользователь в чате
            'is_user_to_chat' => $isChatRoomToUser ? 'true' : null,
            'setting' => $setting,
            'value' => $value,
        ]);
    }
}
