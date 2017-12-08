<template>
    <span class="overlay-loader center">
        <img src="https://dev-43256.ciliatus.io/svg/Ciliatus_Logo.svg"
             class="loader-icon spinning-logo"
             :style="'width: ' + size + 'px; width: ' + size + 'px;'">
    </span>
</template>

<script>
export default {
    data () {
        return {}
    },

    props: {
        size: {
            type: Number,
            default: 100,
            required: false
        }
    },

    methods: {},

    created: function() {}
}
</script>
