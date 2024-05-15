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
     * @param $chatId
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @throws \Exception
     */
    public function getSettings($chatId, $userId)
    {
        try {
            $chatUserSettings = $this->model::query()->where('chat_room_id', $chatId)->where('user_id', $userId)->first();
            if(!$chatUserSettings){
                $chatUserSettings = $this->initSettings($chatId,  $userId);
            }
            return $chatUserSettings;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.get_data'));
        }
    }

    /**
     * Добавляем настройки чата пользователя
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function createSettings($data){
        try{
            $chatUserSettings = $this->model::query()->create($data);
            return $chatUserSettings;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.add_data'));
        }
    }

    /**
     * Добавляем настройки чата если их еще нет
     * @param $chatId
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function initSettings($chatId, $userId){
        try {
            $chatUserSettings = $this->model::query()->create([
                'chat_room_id' => $chatId,
                'user_id' => $userId,
            ]);
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.add_data'));
        }

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
        try {
            $chatUserSettings = ChatUserSettings::query()->where('user_id', $data['user_id'])
                ->where('chat_room_id', $chatId)
                ->first();

            $chatUserSettings->update([
                $data['setting'] => $data['value']
            ]);
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.update_data'), 422);
        }
    }
}
