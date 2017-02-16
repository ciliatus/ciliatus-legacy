<template>
    <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
        <div v-for="generic_component in generic_components">
            <div :class="wrapperClasses">
                <div class="card">
                    <div class="card-content teal lighten-1 white-text">
                        <i class="material-icons">{{ generic_component.type.icon }}</i>
                        {{ generic_component.type.name_singular }}
                    </div>

                    <div class="card-content">
                        <span class="card-title activator truncate">
                            <span>{{ generic_component.name }}</span>
                            <i class="material-icons right">more_vert</i>
                        </span>
                        <p>
                            <span v-for="(value, name) in generic_component.properties">{{ name }}: {{ value }}<br /></span>
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/generic_components/' + generic_component.id">{{ $t("buttons.details") }}</a>
                        <a v-bind:href="'/generic_components/' + generic_component.id + '/edit'">{{ $t("buttons.edit") }}</a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ $tc('components.controlunits', 1) }}<i class="material-icons right">close</i></span>
                        <p>
                            <a :href="'/controlunits/' + generic_component.controlunit.id">{{ generic_component.controlunit.name }}</a>
                        </p>
                        <span class="card-title grey-text text-darken-4">{{ generic_component.belongsTo_type }}</span>
                        <p>
                            <a v-if="generic_component.belongsTo" :href="generic_component.belongsTo.url">{{ generic_component.belongsTo.name }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            generic_components: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        genericComponentId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
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
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'generic_components-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function(gc) {
            var item = null;
            this.generic_components.forEach(function(data, index) {
                if (data.id === gc.generic_component.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.generic_components.push(gc.generic_component);
            }
            else if (item !== null) {
                this.generic_components.splice(item, 1, gc.generic_component);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        delete: function(gc) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.generic_components.forEach(function(data, index) {
                if (data.id === gc.generic_component_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.generic_components.splice(item, 1);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        refresh_grid: function() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/generic_components/' + that.genericComponentId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    if (that.genericComponentId !== '') {
                        that.generic_components = [data.data];
                    }
                    else {
                        that.generic_components = data.data;
                    }

                    that.$nextTick(function() {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col',
                        });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('GenericComponentUpdated', (e) => {
                this.update(e);
            }).listen('GenericComponentDeleted', (e) => {
                this.delete(e);
            });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }
}
</script>
