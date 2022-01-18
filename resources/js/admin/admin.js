require('../bootstrap');

import Vue from 'vue';

import vuetify from '../vuetify';
import router from './router';
import store from './store';


import App from './App.vue';

new Vue({
    store,
    router,
    vuetify,
    render: h => h(App),
    el: '#app',
});
