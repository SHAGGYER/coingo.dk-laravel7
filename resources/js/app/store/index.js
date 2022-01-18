import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

import helpers from "../../helpers/helpers";

import auth from "./auth";
import places from "./places";
import items from "./items";
import suppliers from "./suppliers";
import contacts from "./contacts";
import companies from "./companies";
import invoices from "./invoices";
import accounting from "./accounting";
import users from "./users";
import feedback from "./feedback";

export default new Vuex.Store({
  state: {
    loading: false,
    user: null,
    req: axios.create({
      baseURL: BASE_URL
    }),
    helpers,
    isSubscribed: false,
    onGracePeriod: false,
    recaptchaSiteKey: '6LcDaMMUAAAAAF3jm7Ndi0UzepTVMg5xWCZWzWqd',
    sidebarToggle: false
  },
  mutations: {
    setSidebarToggle(state) {
      state.sidebarToggle = !state.sidebarToggle;
    },
    setLoading(state, loading) {
      state.loading = loading;
    },
    setUser(state, user) {
      state.user = user;
    },
    setIsSubscribed(state, isSubscribed) {
      state.isSubscribed = isSubscribed;
    },
    setOnGracePeriod(state, onGracePeriod) {
      state.onGracePeriod = onGracePeriod;
    }
  },
  actions: {
    updateHasSeenTour({commit, state}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        state.req.post('users/updateHasSeenTour').then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    }
  },
  modules: {
    auth,
    places,
    items,
    suppliers,
    contacts,
    companies,
    accounting,
    invoices,
    users,
    feedback
  }
})
