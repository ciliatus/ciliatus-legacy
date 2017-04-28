<template>
    <div>
        <div class="white-text">
            <div class="input-field inline">
                <form action="/api/v1/apiai/send_request" data-method="POST" id="api-io-widget-form"
                      :data-no-confirm="true" v-on:submit="submit_interceptor">
                    <input name="speech" id="ask_me_something" type="text"
                           :readonly="loading"
                           class="validate" style="padding: 0;" autocomplete="off">
                    <label for="ask_me_something" class="white-text">{{ $t('labels.ask_me_something') }}</label>
                </form>
            </div>


            <a class="btn-floating waves-effect waves-light" v-show="!loading">
                <i v-show="!recording" v-on:click="record" class="material-icons">mic_none</i>
                <i v-show="recording" v-on:click="record" class="material-icons">mic</i>
            </a>

                <div class="preloader-wrapper small active" v-show="loading"
                     style="margin-left: 18px; margin-right: 18px; top: 10px;">
                    <div class="spinner-layer spinner-blue-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                        <div class="circle-clipper right">
                        <div class="circle"></div>
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
            loading: false,
            recording: false,
            result: null
        }
    },

    props: {

    },

    methods: {
        submit_interceptor: function(e) {
            if (this.loading) {
                e.preventDefault();
                return;
            }

            this.loading = true;
        },
        parse_result: function(data) {
            if (data.source_id !== 'api-io-widget-form') {
                return;
            }

            this.result = data.data;
            this.loading = false;

            if (this.result.data.api_result.result !== undefined) {
                $('#api-io-widget-result-modal-content').html('' + this.result.data.api_result.result.fulfillment.speech);
            }
            else {
                $('#api-io-widget-result-modal-content').html('' + this.result.data.api_result);
            }
            $('#api-io-widget-result-modal').modal('open');

        },
        record: function () {
            this.recording = !this.recording;
        }
    },

    created: function() {
        window.eventHubVue.$on('FormSubmitReturnedSuccess', this.parse_result);
        this.$nextTick(function() {
            $('#api-io-widget-result-modal').modal();
        })
    }

}
</script>