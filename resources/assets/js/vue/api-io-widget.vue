<template>
    <div>
        <div class="white-text">
            <div class="input-field inline">
                <form action="/api/v1/apiai/send_request" data-method="POST" id="api-io-widget-form"
                      :data-no-confirm="true" v-on:submit="submit_interceptor">
                    <input name="speech" id="ask-me-something" type="text"
                           :readonly="loading" v-model="transcript"
                           class="validate" style="padding: 0;" autocomplete="off">
                    <label for="ask-me-something" id="ask-me-something-label" class="white-text">{{ $t('labels.ask_me_something') }}</label>
                </form>
            </div>


            <a class="btn-floating waves-effect waves-light" v-show="!loading" v-on:mousedown="record" v-on:mouseup="stop_recording">
                <i v-show="!recording" class="material-icons">mic_none</i>
                <i v-show="recording" class="material-icons">mic</i>
            </a>
            <div class="preloader-wrapper small active" v-show="loading"
                 style="margin-left: 18px; margin-right: 18px; top: 10px;">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
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
            var result_text = ''

            if (this.result.data.api_result.result !== undefined) {
                result_text = this.result.data.api_result.result.fulfillment.speech;
            }
            else {
                result_text = this.result.data.api_result
            }

            $('#api-io-widget-result-modal-content').html(result_text);
            $('#api-io-widget-result-modal').modal('open');
            var msg = new SpeechSynthesisUtterance(result_text);
            window.speechSynthesis.speak(msg);
            //$('#ask-me-something').val('');

        },
        record: function() {
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
        window.eventHubVue.$on('FormSubmitReturnedSuccess', this.parse_result);
        if (!('webkitSpeechRecognition' in window)) {
            this.recording_capability = false;
        }
        else {
            var that = this;
            this.recognition = new webkitSpeechRecognition();
            this.recognition.continuous = true;
            this.recognition.interimResults = true;
            this.recognition.lang = $('body').data('lang');

            this.recognition.onstart = function() {
                that.recording = true;
                $('#ask-me-something-label').addClass('active');
            };
            this.recognition.onresult = function(e) {
                var transcript = '';

                for (var i = e.resultIndex; i < e.results.length; ++i) {
                    transcript += e.results[i][0].transcript;
                }

                that.transcript = transcript.replace('/\S/', function(m) { return m.toUpperCase(); });
            };
            this.recognition.onerror = function (e) {
                that.recording = false;

            };
            this.recognition.onend = function () {
                $('#api-io-widget-form').submit();
                that.recording = false;
                that.loading = true;
            };
        }
        this.$nextTick(function() {
            $('#api-io-widget-result-modal').modal();
        })
    }

}
</script>