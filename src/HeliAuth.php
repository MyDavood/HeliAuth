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

            $this->bot->sendConfirmToTelegram(
                telegramId: $user->telegram_id,
                hashId: $hashId,
                ip: $request->ip(),
                browser: $request->userAgent(),
            );

            Cache::put(
                key: $hashId,
                value: [
                    'userId' => $user->id,
                    'telegramId' => $user->telegram_id,
                    'status' => 0,
                ],
                ttl: now()->addMinutes(config('heliAuth.hash_ttl')),
            );

            return $hashId;
        }
        sleep(500);

        return Str::uuid()->toString();
    }
}
