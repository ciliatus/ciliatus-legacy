<template>
    <div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-content teal lighten-1 white-text">
                    {{ $tc("components.animal_feedings", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ $tc("components.animal_feedings", 2) }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="af in animal_feedings">
                        <p>{{ af.timestamps.created }} - {{ $t("labels." + af.type) }}</p>
                    </div>
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
            animal_feedings: []
        }
    },

    props: {
        animalId: {
            type: String,
            required: true
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            if (a.animal_feeding.animal.id !== this.animalId) {
                return;
            }

            this.animal_feedings.forEach(function(data, index) {
                if (data.id === a.animal_feeding.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_feedings.push(a.animal_feeding)
            }
            else if (item !== null) {
                this.animal_feedings.splice(item, 1, a.animal_feeding);
            }
        },

        delete: function(a) {
            var item = null;
            this.animal_feedings.forEach(function(data, index) {
                if (data.id === a.animal_feeding.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_feedings.splice(item, 1);
            }
        },

        submit: function(e) {
            window.submit_form(e);
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('AnimalFeedingUpdated', (e) => {
                this.update(e);
            }).listen('AnimalFeedingDeleted', (e) => {
                this.delete(e);
            });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/animals/' + that.animalId + '/feedings?limit=10',
            method: 'GET',
            success: function (data) {
                that.animal_feedings = data.data;
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
