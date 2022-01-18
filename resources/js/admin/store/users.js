export default {
  namespaced: true,
  actions: {
    browse({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('admin/users/browse', { params }).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    resumeSubscription({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('admin/users/resumeSubscription', data).then(() => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
    cancelSubscription({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('admin/users/cancelSubscription', data).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
  }
}
