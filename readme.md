# Laravel Nova Real-time Notification Broadcasting

This package adds real-time notification broadcasting capabilities to Laravel Nova Notifications, seamlessly integrating with Laravel Nova.

## Features

- Real-time notifications broadcasting using Laravel Echo.
- Seamless integration with Laravel Nova.
- Configurable broadcast channels and events.
- Option to play sound on receiving notifications.

## Installation

### Requirements

- Laravel Nova 4.x or higher

### Steps

1. Install the package via Composer:

    ```bash
    composer require yassir3wad/nova-realtime-notification
    ```

2. Publish the package configuration:

    ```bash
    php artisan vendor:publish --provider="Yassir3wad\NovaRealtimeNotification\ToolServiceProvider"
    ```

3. Configure your broadcasting settings in `config/broadcasting.php`. Ensure you have the necessary drivers (e.g., Pusher, Redis, etc.) configured.

## Configuration

The package configuration can be found in `config/nova-realtime-notification.php`. You can customize the following settings:

- `enabled`: Enable/disable real-time notifications.
- `broadcast_driver`: The broadcast driver to be used (e.g., `pusher`, `reverb`).
- `broadcast_channel`: The private broadcast channel for notifications. Supported values are `App.Models.User`, `App.Models.Provider`, and `App.Models.Customer`.
- `enable_sound`: Enable/disable sound for notifications.
- `sound_path`: The path to the sound file in your public directory.

Example configuration (`config/nova-realtime-notification.php`):

```php
return [
    'enabled' => true,
    'broadcast_driver' => 'pusher',
    'broadcast_channel' => 'App.Models.User',
    'enable_sound' => true,
    'sound_path' => 'sounds/sound1.mp3',
];
```

## Usage

### Broadcasting Notifications

1. Create a new notification:

    ```bash
    php artisan make:notification AdminNotification
    ```

2. Implement the `via`, `toNova`, and `toBroadcast` methods in your notification class where you have `NovaChannel`, and add `broadcast` to the `via` method:

    ```php
    namespace App\Notifications;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\BroadcastMessage;
    use Illuminate\Notifications\Notification;
    use Laravel\Nova\Notifications\NovaChannel;
    use Laravel\Nova\Notifications\NovaNotification;

    class AdminNotification extends Notification implements ShouldQueue
    {
        use Queueable;

        private $message;
        private $actionPath;
        private $actionLabel;
        private $level;

        public function __construct($message, $actionPath, $actionLabel, $level)
        {
            $this->message = $message;
            $this->actionPath = $actionPath;
            $this->actionLabel = $actionLabel;
            $this->level = $level;
        }

        public function via($notifiable)
        {
            return [NovaChannel::class, 'broadcast'];
        }

        public function toNova()
        {
            return (new NovaNotification)
                ->message($this->message)
                ->action($this->actionLabel, $this->actionPath)
                ->icon('bell')
                ->type($this->level);
        }

        public function toBroadcast(object $notifiable): BroadcastMessage
        {
            return new BroadcastMessage([
                'message' => $this->message,
                'action_label' => $this->actionLabel,
                'action_path' => $this->actionPath,
                'level' => $this->level,
                'duration' => 7000,
            ]);
        }
    }
    ```

3. Trigger the notification from your application logic:

    ```php
    use App\Notifications\AdminNotification;
    use App\Models\User;

    $user = User::find(1);
    $user->notify(new AdminNotification('You have a new admin message!', '/admin/messages', 'View', 'info'));
    ```

## Contributing

Thank you for considering contributing to the Laravel Nova Real-time Notification Broadcasting package! Please read the [contribution guidelines](CONTRIBUTING.md) for details.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

# Screenshots
![Nova Real-time Notification](https://github.com/yassir3wad/nova-realtime-notification/blob/main/screenshot1.png)
![Nova Real-time Notification](https://github.com/yassir3wad/nova-realtime-notification/blob/main/screenshot2.png)
