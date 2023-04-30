<?php

namespace Heli\Auth;

use Cache;
use Http;
use Illuminate\Http\Request;
use Str;
use Throwable;
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
            $params = [
                'ip' => $this->getIpText($request->getClientIp()),
                'username' => $user->username,
                'code' => $code,
                'browser' => $this->getBrowserText($request->userAgent()),
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

    private function getCountry(string $ip): string|null
    {
        $response = Http::get(sprintf('http://ip-api.com/json/%s?fields=country', $ip))->object();
        return $response->country;
    }

    private function getIpText(string $ip): string
    {
        $country = null;
        try {
            $country = $this->getCountry($ip);
        } catch (Throwable) {}
        if ($country != null) {
            return sprintf('%s (%s)', $ip, $country);
        }
        return $ip;
    }

    private function getBrowserText(string $userAgent): string
    {
        $parser = Parser::create();
        $ua = $parser->parse($userAgent);

        if (blank($ua->os->minor)) {
            return sprintf(
                '%s %s on %s %s',
                $ua->ua->family,
                $ua->ua->major,
                $ua->os->family,
                $ua->os->major,
            );
        }

        return sprintf(
            '%s %s on %s %s.%s',
            $ua->ua->family,
            $ua->ua->major,
            $ua->os->family,
            $ua->os->major,
            $ua->os->minor
        );
    }
}
