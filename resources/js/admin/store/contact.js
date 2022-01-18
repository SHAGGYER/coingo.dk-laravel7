export default {
  namespaced: true,
  actions: {
    browse({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('admin/contact/browse', { params }).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
  }
}
