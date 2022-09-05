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

    public function getSettings($chatId, $userId)
    {
        $chatUserSettings = $this->model::where('chat_room_id', $chatId)->where('user_id', $userId)->first();

        if(!$chatUserSettings){
            $chatUserSettings = $this->createSettings([
                'chat_room_id' => $chatId,
                'user_id' => $userId,
                'show_notification_new_message' => false,
            ]);
        }

        return $chatUserSettings;
    }

    public function createSettings($data){
        $chatUserSettings = $this->model::create($data);

        return $chatUserSettings;
    }

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
