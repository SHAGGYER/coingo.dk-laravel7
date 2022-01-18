export default {
  namespaced: true,
  actions: {
    create({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('companies/create', data).then(response => {
          commit('setLoading', false, { root: true });
          const user = { ...rootState.user };
          user.company = response.data.company;
          commit('setUser', user, {root: true});
          resolve();
        })
      });
    },
    delete({commit, rootState}, id) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('companies/delete/'+id).then(response => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
    update({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('companies/update', data).then(response => {
          commit('setLoading', false, { root: true });
          commit('setUser', {...rootState.user, company: response.data}, { root: true });
          resolve(response.data);
        })
      });
    },
    getById({commit, rootState}, id) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('companies/show/'+id).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },

  }
}
