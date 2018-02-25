window.eventHubVue = new global.Vue({
    props: {
        globalLoadingBarCount: {
            type: Number,
            default: 0,
            required: false
        }
    },

    methods: {
        processStarted: function() {
            this.globalLoadingBarCount++;
            this.checkLoadingBarState();
        },

        processEnded: function() {
            this.globalLoadingBarCount--;
            this.checkLoadingBarState();
        },

        checkLoadingBarState: function() {
            if (this.globalLoadingBarCount > 0) {
                $('.main-loader').addClass('spinning-logo');
                //$('#global-loading-bar').show();
            }
            else {
                $('.main-loader').one('animationiteration webkitAnimationIteration', function() {
                    $(this).removeClass('spinning-logo');
                });
                //$('#global-loading-bar').hide();
            }
        }
    }
});