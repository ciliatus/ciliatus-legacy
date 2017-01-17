<template>
    <ul>
        <li v-show="echo.connector.pusher.connection.state !== 'connected' && ready"
            class="amber side-menu-info-title">

            <a><i class="material-icons">signal_wifi_off</i> {{ $t('labels.connecting') }}</a>

        </li>
        <li v-show="echo.connector.pusher.connection.state !== 'connected' && ready"
            class="amber side-menu-info">

            <a class="grey-text">{{ $t('tooltips.connecting_to_server') }}</a>

        </li>
    </ul>
</template>

<script>
export default {

    data () {
        return {
            echo: window.echo,
            ready: false
        }
    },

    created () {
        var that = this;
        setTimeout(function() {
            that.ready = true;
        }, 2000);
    }
}
</script>
