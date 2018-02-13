export default class CiliatusObject {

    constructor(type, id) {
        this.data = null;
        this.type = type;
        this.id = id;
        this.max_age_seconds = 60;
        this.last_refresh = 0;
        this.last_persist = 0;
        this.last_change = 0;
        this.api_url = global.apiUrl;

        this.refresh();
    }

    refresh () {
        let that = this;
        $.ajax({
            url: that.url(),
            method: 'GET',
            success: function (data) {
                that.handleApiResult(data);
            },
            error: function (error) {
                console.log(JSON.stringify(error));
            }
        });
    }

    url () {
        return this.api_url + '/' + this.type + '/' + this.id
    }

    handleApiResult (data) {
        this.data = data['data'];
        this.last_change = Date.now();
        this.last_refresh = Date.now();
        this.last_persist = Date.now();
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
}