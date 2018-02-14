/**
 * Setup Vue
 */
import Vuex from 'vuex';

global.Vue = require('vue');
global.Vue.config.errorHandler = function (err, vm, info)  {
    let handler, current = vm;
    if (vm.$options.errorHandler) {
        handler = vm.$options.errorHandler
    } else {
        while (current.$parent) {
            current = current.$parent;
            if (handler = current.$options.errorHandler) break
        }
    }
    if (handler) handler.call(current, err, vm, info);
    else {
        console.log(err);
        window.notification(global.ciliatusVue.$t('errors.frontend.generic'), 'red darken-1 text-white');
    }
};



/**
 * Retrive env variables
 */
global.lang = $('body').data('lang');
global.apiUrl = $('body').data('base-url') + '/api/v1';

/**
 * Localization
 */
import VueI18n from 'vue-i18n'
global.Vue.use(VueI18n);

let locales = require("./lang.js");
let locales_array = [];
Object.keys(locales).forEach(function (lang) {
    locales_array[lang] = locales[lang]
});

global.i18n = new VueI18n({
    locale: global.lang,
    messages: locales_array
});

/**
 * Helper function to format time string
 */
let TimeStringFormatter = Object;
TimeStringFormatter.install = function(Vue, options) {
    global.Vue.prototype.$getMatchingTimeDiff = function(obj) {
        if (obj == null) {
            return {val: null, unit: 'no_data'}
        }
        if (obj.years > 1) {
            return {val: obj.years, unit: 'years_ago'};
        }
        if (obj.months > 1) {
            return {val: obj.months, unit: 'months_ago'};
        }
        if (obj.weeks > 1) {
            return {val: obj.weeks, unit: 'weeks_ago'};
        }
        if (obj.days > 1) {
            return {val: obj.days, unit: 'days_ago'};
        }
        if (obj.hours > 1) {
            return {val: obj.hours, unit: 'hours_ago'};
        }
        if (obj.minutes > 1) {
            return {val: obj.minutes, unit: 'minutes_ago'};
        }
        return {val: null, unit: 'just_now'};
    };
};

global.Vue.use(TimeStringFormatter);


/**
 * Require components
 */
require('./eventhub.js');
require('./ciliatus.js');
require('./ciliatus_object.js');