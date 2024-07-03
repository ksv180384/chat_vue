<?php


namespace App\Services;



use App\Models\Chat\ChatUserSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class ChatUserSettingsService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Получаем настройки чата в котором состоит пользователь
     * @param int $chatId
     * @param int $userId
     * @return Model
     * @throws \Exception
     */
    public function getSettings(int $chatId, int $userId): Model
    {
        try {
            $chatUserSettings = ChatUserSettings::query()->where('chat_room_id', $chatId)->where('user_id', $userId)->first();
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
     * @param array $data
     * @return Model
     * @throws \Exception
     */
    public function createSettings(array $data): Model
    {
        try{
            $chatUserSettings = $this->model::query()->create($data);

            return $chatUserSettings;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.add_data'));
        }
    }

    /**
     * Добавляем настройки чата если их еще нет
     * @param int $chatId
     * @param int $userId
     * @return Model
     * @throws \Exception
     */
    public function initSettings(int $chatId, int $userId): Model
    {
        try {
            $chatUserSettings = ChatUserSettings::query()->create([
                'chat_room_id' => $chatId,
                'user_id' => $userId,
            ]);

            return $chatUserSettings;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.add_data'));
        }
    }

    /**
     * @param int $chatId
     * @param array $data
     * @return Model
     * @throws \Exception
     */
    public function updateSetting(int $chatId, array $data): Model
    {
        try {
            $chatUserSettings = ChatUserSettings::query()->where('user_id', $data['user_id'])
                ->where('chat_room_id', $chatId)
                ->first();

            $chatUserSettings->update([
                'show_notification_new_message' => $data['show_notification_new_message']
            ]);

            return $chatUserSettings;
        } catch (\Exception $e){
            throw new \Exception(config('app_messages.errors.update_data'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
