import Echo from "laravel-echo"

window.echo = new Echo({
    broadcaster: 'pusher',
    key: '0c8933e7ceb3ed05bc9c',
    namespace: 'App\\Events',
    cluster: 'eu'
});
