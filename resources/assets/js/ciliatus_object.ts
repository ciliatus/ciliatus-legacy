export default class CiliatusObject {
    data: object;
    meta: object = {
        type: null,
        id: null,
        max_age_seconds: null,
        last_refresh: null,
        last_persist: null,
        last_change: null
    };

    constructor(type: string, id: string, max_age_seconds: number) {
        this.meta.type = type;
        this.meta.id = id;
        this.meta.max_age_seconds = max_age_seconds;

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
        return global.apiUrl + '/' + this.meta.type + '/' + this.meta.id
    }

    handleApiResult (data) {
        this.data = data;
        this.meta.last_change = Date.now();
        this.meta.last_refresh = Date.now();
        this.meta.last_persist = Date.now();
    }

    set (key, value) {
        this.data.key = value;
        this.meta.last_update = Date.now();
    }

    pushToApi () {
        this.meta.last_persist = Date.now();
    }
}