<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Chat\ChangeSettingRequest;
use App\Services\ChatUserSettingsService;
use Illuminate\Support\Facades\Auth;

class ChatUserSettingsController  extends BaseController
{
    /**
     * @var ChatUserSettingsService
     */
    private $chatUserSettingsService;

    public function __construct(
        ChatUserSettingsService $chatUserSettingsService
    )
    {
        parent::__construct();

        $this->chatUserSettingsService = $chatUserSettingsService;
    }

    /**
     * Данные для страницы настроек чата
     * @param $chatId
     * @return \Illuminate\Http\JsonResponse
     */
    public function settingsByUser($chatId)
    {
        try {
            $settings = $this->chatUserSettingsService->getSettings($chatId, Auth::id());
            return response()->json(['settings' => $settings]);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 404);
        }

    }

    /**
     * Меняем настройку чата для пользователю
     * @param int $chatId
     * @param ChangeSettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function settingChangeByUser($chatId, ChangeSettingRequest $request)
    {
        $arRequest = $request->validated();

        try {
            $this->chatUserSettingsService->updateSetting($chatId, $arRequest);
            return response()->json(['message' => 'Настройка чата успешно изменена.']);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
