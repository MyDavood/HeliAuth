<?php

namespace Heli\Auth;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Message;

class AuthBot
{
    public function __construct(
        public readonly BotApi $bot,
    ) {
    }

    public function sendAlert(
        string $telegramId,
        array $params,
        string $hashId,
    ): Message {
        return $this->bot->sendMessage(
            chatId: $telegramId,
            text: view('heliAuth::auth_confirm', $params)->render(),
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

    public function updateAlert(
        int|string $messageId,
        int|string $telegramId,
        array $params,
    ): void {
        $this->bot->editMessageText(
            chatId: $telegramId,
            messageId: $messageId,
            text: 'aa',
            parseMode: 'HTML',
        );
    }

    public function removeMessageButtons(
        int|string $messageId,
        int|string $telegramId
    ): void {
        $this->bot->editMessageReplyMarkup(
            chatId: $telegramId,
            messageId: $messageId,
        );
    }
}
