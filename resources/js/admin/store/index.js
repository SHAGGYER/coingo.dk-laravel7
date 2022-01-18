import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

import helpers from "../../helpers/helpers";

import auth from "./auth";
import users from "./users";
import admins from "./admins";
import feedback from "./feedback";
import contact from "./contact";

export default new Vuex.Store({
  state: {
    loading: false,
    user: null,
    req: axios.create({
      baseURL: BASE_URL
    }),
    helpers,
  },
  mutations: {
    setLoading(state, loading) {
      state.loading = loading;
    },
    setUser(state, user) {
      state.user = user;
    },
  },

  modules: {
    auth,
    users,
    admins,
    feedback,
    contact
  }
})
