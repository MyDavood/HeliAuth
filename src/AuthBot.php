<?php

namespace Heli\Auth;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Message;
use UAParser\Parser;

class AuthBot
{
    public function __construct(
        public readonly BotApi $bot,
    ) {
    }

    public function sendConfirmToTelegram(
        string $username,
        string $telegramId,
        int $code,
        string $hashId,
        string $ip,
        string $browser,
        int $status = 0,
    ): Message {
        $parser = Parser::create();
        $ua = $parser->parse($browser);

        return $this->bot->sendMessage(
            chatId: $telegramId,
            text: view('heliAuth::auth_confirm', [
                'ip' => $ip,
                'username' => $username,
                'code' => $code,
                'ua' => $ua,
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

    public function removeMessageButtons(
        int|string $messageId,
        int|string $userId
    ): void {
        $this->bot->editMessageReplyMarkup(
            chatId: $userId,
            messageId: $messageId,
        );
    }
}
