<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Chat\ChangeSettingRequest;
use App\Services\ChatUserSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ChatUserSettingsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Данные для страницы настроек чата
     * @param int $chatId
     * @param ChatUserSettingsService $chatUserSettingsService
     * @return \Illuminate\Http\JsonResponse
     */
    public function settingsByUser(int $chatId, ChatUserSettingsService $chatUserSettingsService): JsonResponse
    {
        try {
            $settings = $chatUserSettingsService->getSettings($chatId, Auth::id());
            return response()->json(['settings' => $settings]);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

    }

    /**
     * Меняем настройку чата для пользователю
     * @param int $chatId
     * @param ChangeSettingRequest $request
     * @param ChatUserSettingsService $chatUserSettingsService
     * @return \Illuminate\Http\JsonResponse
     */
    public function settingChangeByUser(
        int $chatId,
        ChangeSettingRequest $request,
        ChatUserSettingsService $chatUserSettingsService
    ): JsonResponse
    {
        $arRequest = $request->validated();

        try {
            $chatUserSettingsService->updateSetting($chatId, $arRequest);
            return response()->json(['message' => 'Настройка чата успешно изменена.']);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
