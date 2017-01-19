<template>
    <transition name="fade">
        <ul class="side-menu-info-container" v-show="echo.connector.pusher.connection.state !== 'connected' && ready">

            <li class="amber side-menu-info-title">
                <a><i class="material-icons">signal_wifi_off</i> {{ $t('labels.connecting') }}</a>
            </li>

            <li class="amber side-menu-info-content">
                <a class="grey-text">{{ $t('tooltips.connecting_to_server') }}</a>
            </li>
        </ul>

    </transition>
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
