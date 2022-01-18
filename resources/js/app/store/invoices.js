export default {
  namespaced: true,
  actions: {
    sendInvoiceToEmail({commit, rootState}, data) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.post('invoices/sendInvoiceToEmail', data).then(response => {
          commit('setLoading', false, { root: true });
          resolve();
        }).catch(error => {
          commit('setLoading', false, {root: true});
          reject(error.response.data.message)
        });
      });
    },
    create({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('invoices/create', data).then(response => {
          commit('setLoading', false, { root: true });
          let user = rootState.user;
          user.company.invoice_number = response.data.newInvoiceNumber;
          commit('setUser', user, {root: true});
          resolve(response.data.invoice);
        })
      });
    },
    registerPayment({commit, rootState}, data) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('invoices/registerPayment', data).then(response => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
    delete({commit, rootState}, id) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.post('invoices/delete/'+id).then(response => {
          commit('setLoading', false, { root: true });
          resolve();
        })
      });
    },
    getById({commit, rootState}, data) {
      return new Promise((resolve, reject) => {
        commit('setLoading', true, { root: true });
        rootState.req.get('invoices/show/'+data.id, { params: data.params }).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        }).catch(error => {
          commit('setLoading', false, { root: true });
          reject(error.response.data.message);
        })
      });
    },
    browse({commit, rootState}, params) {
      return new Promise(resolve => {
        commit('setLoading', true, { root: true });
        rootState.req.get('invoices/browse', { params: params }).then(response => {
          commit('setLoading', false, { root: true });
          resolve(response.data);
        })
      });
    }
  }
}
