import Echo from "laravel-echo"
import Pusher from 'pusher-js'

window.echo = new Echo({
    broadcaster: 'pusher',
    key: global.lang = $('body').data('pusher-app-key'),
    namespace: 'App\\Events',
    cluster: 'eu',
    encrypted: true
});
