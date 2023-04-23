<?php

namespace Heli\Auth\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthorizeStatusEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly string $oldHash,
        public readonly string $newHash,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('auth'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'authorized-status';
    }

    public function broadcastWith(): array
    {
        return [
            'oldHash' => $this->oldHash,
            'newHash' => $this->newHash,
        ];
    }
}
