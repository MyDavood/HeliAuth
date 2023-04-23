<?php

namespace Heli\Auth;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class AuthBot
{
    public function __construct(
        public readonly BotApi $bot,
    ) {
    }

    public function sendConfirmToTelegram(
        string $telegramId,
        string $hashId,
        string $ip,
        string $browser,
        int $status = 0,
    ): void {
        $this->bot->sendMessage(
            chatId: $telegramId,
            text: view('heliAuth::auth_confirm', [
                'ip' => $ip,
                'browser' => $browser,
                'url' => str_replace(['http://', 'https://', 'www.'], '', url('/backend')),
                'status' => $status,
            ])->render(),
            parseMode: 'HTML',
            replyMarkup: new InlineKeyboardMarkup(
                inlineKeyboard: [
                    [
                        [
                            'text' => 'Confirm',
                            'callback_data' => sprintf(
                                '%s:%s:%s',
                                config('heliAuth.auth_app_name'),
                                $hashId,
                                '1',
                            ),
                        ],
                        [
                            'text' => 'Decline',
                            'callback_data' => sprintf(
                                '%s:%s:%s',
                                config('heliAuth.auth_app_name'),
                                $hashId,
                                '2',
                            ),
                        ],
                    ],
                ],
            ),
        );
    }
}
