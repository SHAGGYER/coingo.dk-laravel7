export default {
  namespaced: true,
  actions: {

    init({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('auth/init').then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', response.data.user, { root: true });
          commit('setIsSubscribed', response.data.isSubscribed, { root: true });
          commit('setOnGracePeriod', response.data.onGracePeriod, { root: true });
          resolve();
        })
      });
    },


    logout({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('auth/logout').then(response => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },


  }
}
