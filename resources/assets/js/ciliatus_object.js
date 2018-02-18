export default class CiliatusObject {

    constructor(type, id, data) {
        this.init_done = false;
        this.type = type;
        this.id = id;
        this.data = data;
        this.max_age_seconds = 60;
        this.last_refresh = 0;
        this.last_persist = 0;
        this.last_change = 0;
        this.api_url = global.apiUrl;

        this.__verifyId();

        if (!this.data) {
            this.refresh();
        }
    }

    refresh () {
        jQuery.ajax({
            context: this,
            url: this.url(),
            method: 'GET',
            success: this.handleApiResult
        })
    }

    url () {
        return this.api_url + '/' + this.type + '/' + this.id;
    }

    handleApiResult (result) {
        this.data = result['data'];
        this.last_change = Date.now();
        this.last_refresh = Date.now();
        this.last_persist = Date.now();

        this.__verifyId();

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