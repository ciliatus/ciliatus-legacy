<template>
    <div>
        <div :class="wrapperClasses">
            <div :id="'modal_add_feeding_' + animalId" class="modal" style="min-height: 800px;">
                <form :action="'/api/v1/animals/' + animalId + '/feedings'" data-method="POST" onsubmit="window.submit_form">
                    <div class="modal-content">
                        <h4>{{ $t("labels.just_fed") }}</h4>

                        <select name="meal_type" id="meal_type">
                            <option v-for="ft in feeding_types" :value="ft">{{ ft }}</option>
                        </select>
                        <label for="meal_type">{{ $t("labels.meal_type") }}</label>

                        <input type="text" id="date-feeding-created" class="datepicker" :placeholder="$t('labels.date')" name="created_at">
                        <label for="date-feeding-created">{{ $t('labels.date') }}</label>
                    </div>

                    <div class="modal-footer">
                        <button class="btn modal-action modal-close waves-effect waves-light" type="submit">{{ $t("buttons.save") }}
                            <i class="mdi mdi-18px mdi-floppy left"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-18px mdi-silverware-variant"></i>
                    {{ $tc("labels.animal_feedings", 2) }}
                </div>

                <div class="card-content">

                    <div v-for="feeding in feedings">
                        <div class="row row-no-margin">
                            <span v-if="feeding.data.timestamps.created_diff.days > 1">{{ $t('units.days_ago', {val: feeding.data.timestamps.created_diff.days}) }}</span>
                            <span v-if="feeding.data.timestamps.created_diff.days <= 1 && feeding.data.timestamps.created_diff.hours > 1">{{ $t('units.hours_ago', {val: feeding.data.timestamps.created_diff.hours}) }}</span>
                            <span v-if="feeding.data.timestamps.created_diff.days <= 1 && feeding.data.timestamps.created_diff.hours <= 1">{{ $t('units.just_now') }}</span>
                            <span> - {{ feeding.data.type }}</span>
                            <span class="right"><a class="red-text" :href="'/animals/' + animalId + '/feedings/' + feeding.data.id + '/delete'"><i class="mdi mdi-18px mdi-delete"></i></a></span>
                        </div>
                    </div>
                    <div v-if="feedings.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>

                    <pagination ref="pagination"
                                :source-filter="sourceFilter"
                                :enable-filters="false"
                                :mini="true">
                    </pagination>
                </div>

                <div class="card-action">
                    <a :href="'#modal_add_feeding_' + animalId"
                       :onclick="'$(\'#modal_add_feeding_' + animalId + '\').modal(); $(\'#modal_add_feeding_' + animalId + ' select\').formSelect(); $(\'#modal_add_feeding_' + animalId + '\').modal(\'open\');'">{{ $t("buttons.add") }}</a>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import pagination from './mixins/pagination.vue';

    export default {
        data () {
            return {
                ids: [],
                feeding_types: []
            }
        },

        props: {
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            },
            animalId: {
                type: String,
                required: true
            },
            sourceFilter: {
                type: String,
                default: '',
                required: false
            },
            hideCols: {
                type: Array,
                default: function(){return [];},
                required: false
            },
            itemsPerPage: {
                type: Number,
                default: 10,
                required: false
            }
        },

        components: {
            pagination
        },

        computed: {
            feedings () {
                let that = this;
                return this.$store.state.animal_feedings.filter(function(a) {
                    return that.ids.includes(a.id) && a.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
                });
            }
        },

        methods: {
            load_types: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/animals/feedings/types',
                    method: 'GET',
                    success: function (data) {
                        that.feeding_types = data.data;
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            },

            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/animals/' + that.animalId + '/feedings?' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(a => a.id);

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('animal_feedings', that.ids, data.data);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            },

            unsubscribe_all () {
                this.feedings.forEach((f) => f.unsubscribe());
            }
        },

        created: function() {
            this.load_types();

            let that = this;
            setTimeout(function() {
                that.$refs.pagination.order.field = 'created_at';
                that.$refs.pagination.order.direction = 'desc';
                that.$refs.pagination.init();
            }, 100);
        }
    }
</script>
