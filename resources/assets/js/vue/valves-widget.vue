<template>
    <div>
        <div :class="wrapperClasses" v-for="valve in valves">
            <div class="card">
                <div class="card-content teal lighten-2 white-text">
                    {{ $tc("components.valves", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ valve.name }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/valves/' + valve.id">{{ $t("buttons.details") }}</a>
                    <a v-bind:href="'/valves/' + valve.id + '/edit'">{{ $t("buttons.edit") }}</a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>
                    <p>

                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            valves: []
        }
    },

    props: {
        valveId: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(cu) {
            var item = null;
            this.valves.forEach(function(data, index) {
                if (data.id === cu.valve.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true1) {
                this.valves.push(cu.valve);
            }
            else if (item !== null) {
                this.valves.splice(item, 1, cu.valve);
            }
        },

        delete: function(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.valves.forEach(function(data, index) {
                if (data.id === cu.valve.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.valves.splice(item, 1);
            }
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
                .listen('valveUpdated', (e) => {
                this.update(e);
        }).listen('valveDeleted', (e) => {
                this.delete(e);
        });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/valves/' + that.valveId,
            method: 'GET',
            success: function (data) {
                if (that.valveId !== '') {
                    that.valves = [data.data];
                }
                else {
                    that.valves = data.data;
                }

                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }
}
</script>
