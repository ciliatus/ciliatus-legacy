<template>
    <div>

        <transition name="fade">
            <div class="side-menu-info"
                 v-show="echo.connector.pusher.connection.state !== 'connected'
                      && echo.connector.pusher.connection.state !== 'initialized'
                      && ready">

                <div class="side-menu-info-title">
                    <i class="material-icons">signal_wifi_off</i> {{ $t('labels.connecting') }}
                </div>
                <div class="side-menu-info-content">
                    <span>{{ $t('tooltips.connecting_to_server') }}</span>
                </div>
            </div>
        </transition>

        <transition name="fade">
            <div class="side-menu-info" v-show="system.emergency_stop === true">
                <div class="side-menu-info-title">
                    <i class="material-icons">power_settings_new</i> {{ $t('labels.emergency_stop') }}
                </div>
                <div class="side-menu-info-content">
                    <span>{{ $t('tooltips.emergency_stop') }}</span>
                </div>
            </div>
        </transition>

    </div>
</template>

<script>
export default {

    data () {
        return {
            echo: window.echo,
            ready: false,
            system: {
                emergency_stop: false
            }
        }
    },

    created () {
        var that = this;

        $.ajax({
            url: '/api/v1/dashboard/system_status?all=true',
            method: 'GET',
            success: function (data) {
                that.system = data.data;
            },
            error: function (error) {
                console.log(JSON.stringify(error));
            }
        });


        window.echo.private('dashboard-updates').listen('SystemStatusUpdated', (e) => {
            this.system = e.system_status;
        });

        setTimeout(function() {
            that.ready = true;
        }, 2000);
    }

}
</script>
