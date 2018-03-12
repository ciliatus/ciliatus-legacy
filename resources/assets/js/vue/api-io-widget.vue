<template>
    <div>
        <div class="white-text">
            <div class="input-field inline" style="min-width: 180px">
                <form action="/api/v1/apiai/send_request" data-method="POST"
                      id="api-io-widget-form" class="ciliatus-search-wrapper"
                      :data-no-confirm="true" v-on:submit="submit_interceptor">
                    <i class="material-icons prefix">lightbulb_outline</i>
                    <input name="speech" id="ask-me-something" type="text"
                           :readonly="loading" v-model="transcript"
                           class="validate" style="padding: 0" autocomplete="off">
                    <label for="ask-me-something" style="margin-left: 3em !important"
                           id="ask-me-something-label" class="white-text">
                        {{ $t('labels.ask_me_something') }}
                    </label>
                </form>
            </div>


            <a class="btn-floating waves-effect waves-light" v-show="!loading"
               v-on:mousedown="record" v-on:mouseup="stop_recording">
                <i v-show="!recording" class="material-icons">mic_none</i>
                <i v-show="recording" class="material-icons">mic</i>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            loading: false,
            recording: false,
            recording_capability: true,
            result: null,
            recognition: null,
            transcript: ''
        }
    },

    props: {

    },

    methods: {
        submit_interceptor: function(e) {
            if (!this.recording_capability) {
                window.notification(global.ciliatusVue.$t('errors.frontend.no_recording_capability'), 'red darken-1 text-white');
                return;
            }
            if (this.loading) {
                e.preventDefault();
                return;
            }

            this.loading = true;
            window.eventHubVue.processStarted();
        },

        parse_result: function(data) {
            if (data.source_id !== 'api-io-widget-form') {
                return;
            }

            this.result = data.data;
            this.loading = false;
            window.eventHubVue.processEnded();
            let result_text = '';

            if (this.result.data.api_result.result !== undefined) {
                result_text = this.result.data.api_result.result.fulfillment.speech;
            }
            else {
                result_text = this.result.data.api_result
            }

            $('#api-io-widget-result-modal-content').html(result_text);
            $('#api-io-widget-result-modal').modal('open');
            let msg = new SpeechSynthesisUtterance(result_text);
            window.speechSynthesis.speak(msg);
            //$('#ask-me-something').val('');

        },
        
        record: function() {
            if (!this.recording_capability) {
                window.notification(global.ciliatusVue.$t('errors.frontend.no_recording_capability'), 'red darken-1 text-white');
                return;
            }
            this.recording = !this.recording;
            if (this.recording) {
                this.recognition.start();
            }
            else {
                this.stop();
            }
        },
        stop_recording: function(){
            this.recording = false;
            this.recognition.stop();
        }
    },

    created: function() {
        this.recording_capability = true;

        if (!('webkitSpeechRecognition' in window)) {
            this.recording_capability = false;
        }
        else {
            window.eventHubVue.$on('FormSubmitReturnedSuccess', this.parse_result);
            this.recognition = new webkitSpeechRecognition();
            this.recognition.continuous = true;
            this.recognition.interimResults = true;
            this.recognition.lang = $('body').data('lang');

            this.recognition.onstart = () => {
                this.recording = true;
                $('#ask-me-something-label').addClass('active');
            };

            this.recognition.onresult = (e) => {
                let transcript = '';

                for (let i = e.resultIndex; i < e.results.length; ++i) {
                    transcript += e.results[i][0].transcript;
                }

                this.transcript = transcript.replace('/\S/', (m) => { return m.toUpperCase() });
            };

            this.recognition.onerror = () => {
                this.recording = false;
            };

            this.recognition.onend = () => {
                $('#api-io-widget-form').submit();
                this.recording = false;
                this.loading = true;
            };
        }

        this.$nextTick(() => $('#api-io-widget-result-modal').modal());
    }

}
</script>