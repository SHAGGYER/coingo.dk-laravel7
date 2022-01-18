export default {
  namespaced: true,
  actions: {
    create({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('places/create', data).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    delete({commit, rootState}, id) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('places/delete/'+id).then(response => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
    update({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('places/update', data).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    getById({commit, rootState}, id) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('places/show/'+id).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    browse({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('places/browse', { params: params }).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    }
  }
}
