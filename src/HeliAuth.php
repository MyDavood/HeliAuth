<?php

namespace Heli\Auth;

use Cache;
use Crypt;
use Str;

class HeliAuth
{
    public function __construct(
        private readonly AuthBot $bot,
    ) {
    }

    public function sendAlert($user): string
    {
        if ($user != null) {
            $request = request();
            $hashId = Str::uuid()->toString();
            $code = rand(pow(10, 3), pow(10, 4)-1);

            $this->bot->sendConfirmToTelegram(
                username: $user->username,
                telegramId: $user->telegram_id,
                code: $code,
                hashId: $hashId,
                ip: $request->getClientIp(),
                browser: $request->userAgent(),
            );

            Cache::put(
                key: $hashId,
                value: [
                    'userId' => $user->id,
                    'code' => $code,
                    'telegramId' => $user->telegram_id,
                    'status' => 0,
                ],
                ttl: now()->addMinutes(config('heliAuth.hash_ttl')),
            );

            return $hashId;
        }
        sleep(1);

        return Str::uuid()->toString();
    }
}
