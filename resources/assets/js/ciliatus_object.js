export default class CiliatusObject {

    constructor(type, id, data, include) {
        this.type = type;
        this.id = id;
        this.data = data;
        this.include = include ? include : [];
        this.max_age_seconds = 60;
        this.api_url = global.apiUrl;
        this.init_done = false;
        this.refreshing = false;

        this.__verifyId();

        if (!this.data) {
            this.refresh();
        }
        else {
            this.last_change = 0;
            this.last_refresh = Date.now();
            this.last_persist = 0
        }
    }

    refresh () {
        this.refreshing = true;
        jQuery.ajax({
            context: this,
            url: this.url(),
            method: 'GET',
            success: this.handleApiResult
        })
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

        if (!this.init_done) {
            window.echo.private('dashboard-updates')
                .listen(this.data.class + 'Updated', (e) => {
                    this.refresh()
                }).listen(this.data.class + 'Deleted', (e) => {
                    this.data = null;
                });

            this.init_done = true;
        }
    }

    persist () {
        let that = this;
        $.ajax({
            url: that.url(),
            data: that.data,
            method: 'PUT',
            success: function (data) {
                console.log('OK');
            },
            error: function (error) {
                console.log(JSON.stringify(error));
            }
        });
        this.last_persist = Date.now();
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