<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable/Disable Realtime Notification
    |--------------------------------------------------------------------------
    |
    | This option controls whether the realtime notification is enabled or not.
    |
    */
    'enabled' => env('NOVA_REALTIME_NOTIFICATION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the broadcast driver that will be used by the package
    |
    */
    'broadcast_driver' => env('BROADCAST_DRIVER'),

    /*
    |--------------------------------------------------------------------------
    | Private Broadcast Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the private broadcast channel for the notification
    | values: App.Models.User, App.Models.Provider, App.Models.Customer
    | The current login user id will be appended to the channel automatically by the package
    |
    */
    'broadcast_channel' => 'App.Models.User',

    /*
    |--------------------------------------------------------------------------
    | Sound Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls whether the sound is enabled or not for the notification
    | values: true, false
    |
    */
    'enable_sound' => true,

    /*
    |--------------------------------------------------------------------------
    | Sound Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the sound mp3 path for the notification
    | values must be a valid path in your public directory
    |
    */
    'sound_path' => 'sounds/sound1.mp3',
];
