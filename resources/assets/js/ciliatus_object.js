export default class CiliatusObject {

    constructor(ciliatus, type, id, data, include) {
        this.ciliatus = ciliatus;
        this.type = type;
        this.id = id;
        this.data = data;
        this.include = include ? include : [];
        this.max_age_seconds = 60;
        this.api_url = global.apiUrl;
        this.refreshing = false;
        this.subscribed = 1;

        this.__verifyId();

        if (!this.data) {
            this.refresh();
        }
        else {
            this.last_change = 0;
            this.last_refresh = Date.now();
            this.last_persist = 0
        }

        setInterval(() => {
            if (this.subscribed > 0) {
                this.refresh()
            }
        }, 60000);
    }

    refresh (force, timeout) {
        if (
            (!force && !(this.last_change > this.last_refresh))
            || this.last_change === undefined || this.last_refresh === undefined
        ) {
            this.refreshing = true;
            // When handling events it can be useful to wait for duplicate events
            timeout = timeout ? timeout*1000 : 1;
            setTimeout(() => {
                jQuery.ajax({
                    context: this,
                    url: this.url(),
                    method: 'GET',
                    success: this.handleApiResult
                })
            }, timeout);
        }
        else {
            window.console.log(
                'Not updating ' + this.type + ' ' + this.id + '. ' +
                'Last change (' + this.last_change + ') is > last refresh (' + this.last_change + ')'
            );
        }
    }

    url () {
        let includes = this.include.map(i => 'with[]=' + i).join('&');
        return this.api_url + '/' + this.type + '/' + this.id + '?' + includes;
    }

    handleApiResult (result) {
        this.data = result['data'];
        this.last_change = 0;
        this.last_refresh = Date.now();
        this.last_persist = 0;

        this.__verifyId();

        this.refreshing = false;

        window.eventHubVue.$emit('CiliatusObjectUpdated', this);
    }

    persist () {
        let that = this;
        $.ajax({
            url: that.url(),
            data: that.data,
            method: 'PUT',
            success: function (data) {
                this.last_persist = Date.now();
                console.log('OK');
            },
            error: function (error) {
                console.log(JSON.stringify(error));
            }
        });
    }

    subscribe () {
        this.subscribed += 1;
    }

    unsubscribe () {
        this.subscribed -= 1;
    }

    __verifyId () {
        if (this.data && this.data.id !== this.id) {
            console.log('----------------------------');
            console.log('CiliatusModel ' + this.type + ' mismatched ID');
            console.log(this.id + ' <> ' + this.data.id);
            console.log(this);
            return false;
        }

        return true;
    }

}