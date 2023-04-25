<?php

namespace Heli\Auth;

use Cache;
use Illuminate\Http\Request;
use Str;
use UAParser\Parser;

class HeliAuth
{
    public function __construct(
        private readonly AuthBot $bot,
    ) {
    }

    public function sendAlert($user, Request $request): string
    {
        if ($user != null) {
            $hashId = Str::uuid()->toString();
            $code = rand(pow(10, 3), pow(10, 4) - 1);

            $parser = Parser::create();
            $ua = $parser->parse($request->userAgent());
            $params = [
                'ip' => $request->getClientIp(),
                'username' => $user->username,
                'code' => $code,
                'ua' => $ua,
                'browser' => sprintf(
                    '%s %s on %s %s,%s',
                    $ua->ua->family,
                    $ua->ua->major,
                    $ua->os->family,
                    $ua->os->major,
                    $ua->os->minor
                ),
                'url' => str_replace(['http://', 'https://', 'www.'], '', url('/backend')),
                'status' => 0,
            ];

            $message = $this->bot->sendAlert(
                telegramId: $user->telegram_id,
                params: $params,
                hashId: $hashId,
            );

            Cache::put(
                key: $hashId,
                value: [
                    'userId' => $user->id,
                    'messageId' => $message->getMessageId(),
                    'code' => $code,
                    'telegramId' => $user->telegram_id,
                    'status' => 0,
                    'params' => $params,
                ],
                ttl: now()->addMinutes(config('heliAuth.hash_ttl')),
            );

            return $hashId;
        }
        sleep(1);

        return Str::uuid()->toString();
    }
}
