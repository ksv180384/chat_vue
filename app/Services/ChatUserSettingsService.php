<?php


namespace App\Services;



use App\Models\Chat\ChatUserSettings;

class ChatUserSettingsService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ChatUserSettings();
    }

    /**
     * Получаем настройки чата в котором состоит пользователь
     * @param int $chatId
     * @param int $userId
     * @return mixed
     */
    public function getSettings($chatId, $userId)
    {
        $chatUserSettings = $this->model::where('chat_room_id', $chatId)->where('user_id', $userId)->first();

        if(!$chatUserSettings){
            $chatUserSettings = $this->initSettings($chatId,  $userId);
        }

        return $chatUserSettings;
    }

    /**
     * Добавляем настройки чата пользователя
     * @param array $data
     * @return mixed
     */
    public function createSettings($data){
        $chatUserSettings = $this->model::create($data);

        return $chatUserSettings;
    }

    /**
     * Добавляем настройки чата если их еще нет
     * @param int $chatId
     * @param int $userId
     * @return mixed
     */
    public function initSettings($chatId, $userId){
        $chatUserSettings = $this->model::create([
            'chat_room_id' => $chatId,
            'user_id' => $userId,
        ]);

        return $chatUserSettings;
    }

    /**
     * Обновляем настройки чата
     * @param int $chatId
     * @param array $data
     * @return void
     */
    public function updateSetting($chatId, $data)
    {
        $chatUserSettings = ChatUserSettings::where('user_id', $data['user_id'])
            ->where('chat_room_id', $chatId)
            ->first();

        $chatUserSettings->update([
            $data['setting'] => $data['value']
        ]);
    }
}
