import Vuex from 'vuex';
import CiliatusObject from 'ciliatus_object.ts';

global.store = new Vuex.Store({
    state: {
        terraria: {

        },

        max_object_age_seconds: 60
    },

    getters: {
        get (object_type, id) {
            return this.__getObject(object_type, id);
        },

        getBulk (object_type, ids) {
            let objects = [];
            ids.forEach(id => objects.append(this.__getObject(object_type, id)));

            return objects;
        }
    },

    setters: {
        set (object_type, id, key, value) {
            let object = this.__getObject(object_type, id);
            object.set(key, value);
        },

        persist (object_type, id) {
            let object = this.__getObject(object_type, id);
            object.pushToApi();
        }
    },

    methods: {
        __createObjectTypeIfNecessary (object_type) {
            if (this.state[object_type] === undefined) {
                this.state[object_type] = {}
            }
        },

        __getObject (object_type, id) {
            this.__createObjectTypeIfNecessary(type);
            if (this.state[object_type][id] === undefined) {
                this.state[object_type][id] = new CiliatusObject(object_type, id, this.max_age_seconds);
            }

            let object = this.state[object_type][id];

            return object;
        },

        __retrieveObjects(object_type, filter) {

        }
    }
});

global.Vue.use(store);