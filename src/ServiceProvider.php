<?php

namespace Heli\Auth;

use Exception;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use TelegramBot\Api\BotApi;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'heliAuth');
        $this->mergeConfigFrom(__DIR__.'/../config/heliAuth.php', 'heliAuth');
        $this->publishes([
            __DIR__.'/../config/heliAuth.php' => config_path('heliAuth.php'),
        ]);
    }

    public function register(): void
    {
        $this->app->singleton(AuthBot::class, function () {
            $botTokens = config('heliAuth.bot_token');
            $domain = request()->getHost();
            $default = false;

            if (isset($botTokens[$domain])) {
                $token = $botTokens[$domain];
            } else if (isset($botTokens['*'])) {
                $token = $botTokens['*'];
                $default = true;
            } else {
                throw new Exception('AuthBot token is null!');
            }

            return new AuthBot(
                new BotApi($token),
                $default,
            );
        });
        $this->app->singleton(HeliAuth::class, function () {
            return new HeliAuth(app(AuthBot::class));
        });
    }
}
