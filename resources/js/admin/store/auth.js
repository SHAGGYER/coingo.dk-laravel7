export default {
  namespaced: true,
  actions: {
    init({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('admin/auth/init').then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', response.data.user, { root: true });
          resolve();
        })
      });
    },
    logout({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('admin/auth/logout').then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', null, { root: true });
          resolve();
        })
      });
    },


  }
}
