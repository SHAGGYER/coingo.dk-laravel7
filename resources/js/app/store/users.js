export default {
  namespaced: true,
  actions: {
    getDashboardData({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('users/getDashboardData').then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    getInvoices({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('users/getInvoices').then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    changePlan({commit, rootState}, data) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/changePlan', data).then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', { ...rootState.user, subscription: response.data }, {root: true});
          resolve();
        }).catch(error => {
          commit('setLoading', false, { root: true });
          reject(error.response.data.message);
        })
      });
    },
    addPlan({commit, rootState}, data) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/addPlan', data).then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', { ...rootState.user, subscription: response.data }, {root: true});
          commit('setIsSubscribed', true, {root: true});
          resolve();
        }).catch(error => {
          commit('setLoading', false, { root: true });
          reject(error.response.data.message);
        })
      });
    },
    resumePlan({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/resumePlan').then(response => {
          commit('setLoading', false, { root: true });
          commit('setIsSubscribed', true, {root: true});
          commit('setOnGracePeriod', false, { root: true });
          resolve();
        })
      });
    },
    cancelPlan({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/cancelPlan').then(response => {
          commit('setLoading', false, { root: true });
          commit('setOnGracePeriod', true, { root: true });
          commit('setUser', { ...rootState.user, subscription: response.data }, {root: true});
          resolve();
        })
      });
    },
    deleteCard({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/deleteCard').then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', { ...rootState.user, card: null }, {root: true});
          resolve();
        })
      });
    },
    addCard({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/addCard', data).then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', { ...rootState.user, card: response.data }, {root: true});
          resolve(response.data);
        })
      });
    },
    changeName({commit, rootState}, data) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/changeName', data).then(() => {
          const user = rootState.user;
          user.name = data.name;
          commit('setUser', user, {root: true});
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
    changeEmail({commit, rootState}, data) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/changeEmail', data).then(() => {
          const user = rootState.user;
          user.email = data.email;
          user.email_verified = 0;
          commit('setUser', user, {root: true});
          commit('setLoading', false, { root: true });
          resolve();
        }).catch(error => {
          reject(error.response.data.message);
          commit('setLoading', false, { root: true });
        });
      });
    },
    changePassword({commit, rootState}, data) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/changePassword', data).then(() => {
          commit('setLoading', false, { root: true });
          resolve();
        }).catch(error => {
          reject(error.response.data.message);
          commit('setLoading', false, { root: true });
        });
      });
    },
    resendEmailVerification({commit, rootState}) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.post('users/resendEmailVerification').then(() => {
          commit('setLoading', false, { root: true });
          resolve();
        }).catch(error => {
          reject(error.response.data.message);
          commit('setLoading', false, { root: true });
        });
      });
    },

  }
}
