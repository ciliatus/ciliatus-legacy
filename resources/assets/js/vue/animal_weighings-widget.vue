<template>
    <div>
        <div :class="wrapperClasses">
            <div :id="'modal_add_weighing_' + animalId" class="modal" style="min-height: 800px;">
                <form :action="'/api/v1/animals/' + animalId + '/weighings'" data-method="POST" onsubmit="window.submit_form">
                    <div class="modal-content">
                        <h4>{{ $t("labels.add_weight") }}</h4>
                        <p>
                            <input type="text" name="weight" id="weight" :placeholder="$t('labels.weight')" value="">
                            <label for="weight">{{ $t("labels.weight") }}/g</label>

                            <input type="date" class="datepicker" :placeholder="$t('labels.date')" name="created_at">
                            <label>{{ $t('labels.date') }}</label>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn modal-action modal-close waves-effect waves-light" type="submit">
                            {{ $t("buttons.save") }}
                            <i class="mdi mdi-18px mdi-floppy left"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-18px mdi-weight-kilogram"></i>
                    {{ $tc("labels.animal_weighings", 2) }}
                </div>

                <div class="card-content">
                    <div v-for="weighing in weighings">
                        <div style="width: 100%;" class="row row-no-margin" v-if="weighing.data">
                            <span v-if="weighing.data.timestamps.created_diff.days > 1">
                                {{ $t('units.days_ago', {val: weighing.data.timestamps.created_diff.days}) }}
                            </span>

                            <span v-if="weighing.data.timestamps.created_diff.days <= 1 &&
                                        weighing.data.timestamps.created_diff.hours > 1">
                                {{ $t('units.hours_ago', {val: weighing.data.timestamps.created_diff.hours}) }}
                            </span>

                            <span v-if="weighing.data.timestamps.created_diff.days <= 1 &&
                                        weighing.data.timestamps.created_diff.hours <= 1">
                                {{ $t('units.just_now') }}
                            </span>

                            <span> - {{ weighing.data.amount }}g</span>

                            <span class="right">
                                <a class="red-text"
                                   :href="'/animals/' + animalId + '/weighings/' + weighing.data.id + '/delete'">
                                    <i class="mdi mdi-18px mdi-delete"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div v-if="weighings.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>

                    <pagination ref="pagination"
                                :source-filter="sourceFilter"
                                :enable-filters="false"
                                :mini="true">
                    </pagination>
                </div>

                <div class="card-action">
                    <a :href="'#modal_add_weighing_' + animalId"
                       :onclick="'$(\'#modal_add_weighing_' + animalId + '\').modal(); $(\'#modal_add_weighing_' + animalId + ' select\').material_select(); $(\'#modal_add_weighing_' + animalId + '\').modal(\'open\');'">
                        {{ $t("buttons.add") }}
                    </a>
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
                file_ids: []
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
            weighings () {
                let that = this;
                return this.$store.state.animal_weighings.filter(function(a) {
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
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/animals/' + that.animalId + '/weighings?' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(a => a.id);

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('animal_weighings', that.ids, data.data);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            },

            unsubscribe_all () {
                this.weighings.forEach((w) => w.unsubscribe());
            }
        },

        created: function() {
            let that = this;
            setTimeout(function() {
                that.$refs.pagination.order.field = 'created_at';
                that.$refs.pagination.order.direction = 'desc';
                that.$refs.pagination.init();
            }, 100);
        }
    }
</script>