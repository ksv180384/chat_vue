<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateChatRequest extends FormRequest
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
            'title' => 'required|min:2',
            'creator_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Введите название чата.',
            'title.min' => 'Название чата не должно быть короче (:min) символов.',
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
            'creator_id' => Auth::id(),
        ]);
    }
}
