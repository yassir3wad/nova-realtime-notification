import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const notificationConfig = Nova.config('realtime_notification');
const broadcaster = Nova.config('broadcaster');

if (notificationConfig.enabled) {
    if (notificationConfig.broadcast_driver === 'pusher') {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: broadcaster.key,
            cluster: broadcaster.options.cluster,
            encrypted: false,
            forceTLS: broadcaster.options.useTLS,
        });
    } else if(notificationConfig.broadcast_driver === 'reverb') {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: broadcaster.key,
            wsHost: broadcaster.options.host,
            wsPort: broadcaster.options.port,
            wssPort: broadcaster.options.port,
            forceTLS: broadcaster.options.useTLS,
            enabledTransports: ['ws', 'wss'],
        });
    }
}


if (window.Echo) {
    if (notificationConfig.enable_sound) {
        appendAudioElement(notificationConfig.sound_path);
    }

    window.Echo = window.Echo.private(notificationConfig.broadcast_channel + '.' + Nova.config('userId'))
        .notification(function (notification) {
            let action = {};
            if (notification.action_path) {
                action.onClick = () => Nova.visit(notification.action_path);

                if (notification.action_label) {
                    action.text = notification.action_label;
                }
            }

            Nova.$toasted.show(notification.message, {
                action: action,
                duration: notification.duration || 5000,
                type: notification.level,
            })

            Nova.$emit('refresh-notifications')

            window.playNotificationSound();
        });
}

function appendAudioElement(url) {
    if (!url) {
        return;
    }

    var audio = document.createElement('audio');
    audio.id = 'notification-sound';
    audio.src = url;
    audio.preload = 'auto';
    document.body.appendChild(audio);
}

window.playNotificationSound = function () {
    var audio = document.getElementById('notification-sound');
    if (audio) {
        audio.play();
    }
}
