export default {
  namespaced: true,
  actions: {
    getChartData({commit, rootState}) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('accounting/getChartData').then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    getAccounting({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('accounting/getAccounting', {params}).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    },
    getVat({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('accounting/getVat', {params}).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    }
  }
}
