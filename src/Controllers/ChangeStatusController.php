<?php

namespace Heli\Auth\Controllers;

use Cache;
use Heli\Auth\Events\AuthorizeStatusEvent;
use Heli\Auth\Requests\ChangeStatusRequest;
use Str;

class ChangeStatusController
{
    public function __invoke(
        ChangeStatusRequest $request,
    ) {
        $data = cache($request->hash);
        $newStatus = 3;
        $newHash = Str::uuid()->toString();
        if ($data != null) {
            if ($data['status'] === 0) {
                if ($data['telegramId'] == $request->telegramId) {
                    $data['status'] = $request->status;
                    $newStatus = $request->status;
                }
            }
        }
        Cache::put(
            key: $newHash,
            value: $data,
            ttl: now()->addMinutes(config('heliAuth.hash_ttl')),
        );
        Cache::delete($request->hash);
        event(new AuthorizeStatusEvent(
            oldHash: $request->hash,
            newHash: $newHash,
        ));
        if ($newStatus == 3) {
            abort(400);
        }
    }
}
