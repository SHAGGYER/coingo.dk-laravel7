export default {
  namespaced: true,
  actions: {
    browse({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('admin/admins/browse', { params }).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    getDashboardData({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('admin/admins/getDashboardData').then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    create({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('admin/admins/create', data).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    delete({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('admin/admins/delete', data).then(() => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
  }
}
