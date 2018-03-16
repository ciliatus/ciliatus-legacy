<template>
    <div :class="containerClasses">
        <div :class="wrapperClasses">
            <div class="card">
                <form :action="formUri" data-method="PUT">
                    <div class="card-header">
                        <span class="activator truncate">
                            <span>
                                <i class="mdi mdi-18px mdi-tag"></i>
                                {{ $t('labels.properties') }}
                            </span>
                        </span>
                    </div>

                    <div class="card-content">

                        <strong>{{ $t('labels.bus') }}</strong><br />
                        <span>{{ $t('tooltips.bus_type_edit_form') }}</span>

                        <div class="row">
                            <div class="input-field col s12">
                                <select name="ControlunitConnectivity::bus_type" v-model="bus_type" id="bus_type">
                                    <option></option>
                                    <option value="gpio">GPIO</option>
                                    <option value="i2c">I2C</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" v-show="bus_type == 'i2c'">
                            <div class="input-field col s12 m6 l4">
                                <input type="text" id="i2c_address" :placeholder="$t('labels.i2c_address')"
                                       name="ControlunitConnectivity::i2c_address" v-model="i2c_address">
                                <label for="i2c_address">{{ $t('labels.i2c_address') }}</label>
                            </div>

                            <div class="input-field col s12 m6 l4">
                                <input type="text" id="i2c_multiplexer_address" :placeholder="$t('labels.i2c_multiplexer_address')"
                                       name="ControlunitConnectivity::i2c_multiplexer_address" v-model="i2c_multiplexer_address">
                                <label for="i2c_multiplexer_address">{{ $t('labels.i2c_multiplexer_address') }}</label>
                            </div>

                            <div class="input-field col s12 m6 l4">
                                <input type="text" id="i2c_multiplexer_port" :placeholder="$t('labels.i2c_multiplexer_port')"
                                       name="ControlunitConnectivity::i2c_multiplexer_port" v-model="i2c_multiplexer_port">
                                <label for="i2c_multiplexer_port">{{ $t('labels.i2c_multiplexer_port') }}</label>
                            </div>
                        </div>

                        <div class="row" v-show="bus_type == 'gpio'">
                            <div class="input-field col s12 m6 l6">
                                <input type="text" id="gpio_pin" :placeholder="$t('labels.gpio_pin')"
                                       name="ControlunitConnectivity::gpio_pin" v-model="gpio_pin">
                                <label for="gpio_pin">{{ $t('labels.gpio_pin') }}</label>
                            </div>
                            <div class="input-field col s12 m6 l6 tooltipped" data-position="top"
                                 data-delay="50" data-html="true" :data-tooltip="'<div style=\'max-width: 300px\'>' +
                                        $t('tooltips.gpio_default_high') + '</div>'">
                                <select id="gpio_default_high"
                                        name="ControlunitConnectivity::gpio_default_high" v-model="gpio_default_high">
                                    <option :value="false">{{ $t('labels.no') }}</option>
                                    <option :value="true">{{ $t('labels.yes') }}</option>
                                </select>
                                <label for="gpio_default_high">
                                    {{ $t('labels.gpio_default_high') }}
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="card-action">

                        <div class="row">
                            <div class="input-field col s12">
                                <button class="btn waves-effect waves-light" type="submit">{{ $t('buttons.save') }}
                                    <i class="mdi mdi-18px mdi-floppy left"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data () {
        return {
            physical_sensor_id: '',
            bus_type: '',
            gpio_pin: '',
            gpio_default_high: false,
            i2c_address: '',
            i2c_multiplexer_address: '',
            i2c_multiplexer_port: ''
        }
    },

    props: {
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
        formUri: {
            type: String,
            default: null,
            required: false
        },
        physicalSensorId: {
            type: String,
            default: null,
            required: false
        },
        busType: {
            type: String,
            default: null,
            required: false
        },
        gpioPin: {
            type: String,
            default: null,
            required: false
        },
        gpioDefaultHigh: {
            type: Boolean,
            default: false,
            required: false
        },
        i2cAddress: {
            type: String,
            default: null,
            required: false
        },
        i2cMultiplexerAddress: {
            type: String,
            default: null,
            required: false
        },
        i2cMultiplexerPort: {
            type: String,
            default: null,
            required: false
        }
    },

    methods: {
        change_bus_type: function(e) {
            this.bus_type = e;
        }
    },

    created: function() {
        var that = this;
        this.$nextTick(function() {
            that.physical_sensor_id = that.physicalSensorId;
            if (that.busType !== null) {
                that.bus_type = that.busType;
                that.gpio_pin = that.gpioPin;
                that.gpio_default_high = that.gpioDefaultHigh;
                that.i2c_address = that.i2cAddress;
                that.i2c_multiplexer_address = that.i2cMultiplexerAddress;
                that.i2c_multiplexer_port = that.i2cMultiplexerPort;
            }
            $('select').material_select();
            $('#bus_type').on('change', function () {
                that.change_bus_type($(this)[0].value)
            })
        });
    }
}
</script>
