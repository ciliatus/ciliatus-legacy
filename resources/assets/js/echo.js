import Echo from "laravel-echo"
import Pusher from 'pusher-js'

window.echo = new Echo({
    broadcaster: 'pusher',
    key: '[YOUR PUSHER APP KEY HERE]',
    namespace: 'App\\Events',
    cluster: 'eu'
});
