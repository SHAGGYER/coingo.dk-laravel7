require('../bootstrap');

import Vue from 'vue';

import vuetify from '../vuetify';
import store from '../app/store';
import router from '../app/router';

import VueTour from 'vue-tour';
require('vue-tour/dist/vue-tour.css');
Vue.use(VueTour);

import Notifications from 'vue-notification';
Vue.use(Notifications);

import VueCookies from 'vue-cookies';
Vue.use(VueCookies);

import App from './App.vue';

new Vue({
    store,
    router,
    vuetify,
    render: h => h(App),
    el: '#app',
});
