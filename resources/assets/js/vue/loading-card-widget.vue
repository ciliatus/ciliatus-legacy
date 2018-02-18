<template>
    <div class="card">
        <div class="card-header">
            {{ $t('labels.loading') }}
        </div>

        <div class="card-content center">
            <loading-indicator :size="40"> </loading-indicator>
        </div>
    </div>
</template>

<script>
    import LoadingIndicator from './loading-indicator.vue';

    export default {
        components: {
            'loading-indicator': LoadingIndicator
        }
    }
</script>