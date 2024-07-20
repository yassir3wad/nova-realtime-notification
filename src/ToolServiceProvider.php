<?php

namespace Yassir3wad\NovaRealtimeNotification;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nova-realtime-notification.php' => config_path('nova-realtime-notification.php'),
        ], 'nova-realtime-notification');

        $this->mergeConfigFrom(
            __DIR__.'/../config/nova-realtime-notification.php',
            'nova-realtime-notification'
        );

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-realtime-notification', __DIR__.'/../dist/js/tool.js');

            [$realtimeNotificationConfig, $broadcastConfig] = $this->getConfig();

            Nova::provideToScript([
                'realtime_notification' => $realtimeNotificationConfig,
                'broadcaster' => $broadcastConfig,
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the configuration needed for the JS
     *
     * @return array
     */
    protected function getConfig()
    {
        $realtimeNotificationConfig = config('nova-realtime-notification');
        if (isset($realtimeNotificationConfig['sound_path']) && $realtimeNotificationConfig['sound_path']) {
            $realtimeNotificationConfig['sound_path'] = asset($realtimeNotificationConfig['sound_path']);
        }

        $broadcastConfig = config('broadcasting.connections.'.$realtimeNotificationConfig['broadcast_driver']);
        $broadcastConfig = Arr::only($broadcastConfig, ['key', 'options']);

        return [$realtimeNotificationConfig, $broadcastConfig];
    }
}
