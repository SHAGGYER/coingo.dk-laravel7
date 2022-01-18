export default {
  namespaced: true,
  actions: {
    create({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('feedback/create', data).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    delete({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('feedback/delete', data).then(() => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
    browse({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('feedback/browse', {params}).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    }
  }
}
