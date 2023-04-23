<?php

namespace Heli\Auth;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use TelegramBot\Api\BotApi;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'heliAuth');
        $this->mergeConfigFrom(__DIR__.'/../config/heliAuth.php', 'heliAuth');
    }

    public function register(): void
    {
        $this->app->singleton(AuthBot::class, function () {
            return new AuthBot(
                new BotApi(config('heliAuth.bot_token'))
            );
        });
        $this->app->singleton(HeliAuth::class, function () {
            return new HeliAuth(app(AuthBot::class));
        });
    }
}
