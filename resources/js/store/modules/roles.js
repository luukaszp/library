import axios from 'axios';

export default {
  state: {
    roles: []
  },
  mutations: {
    setRoles(state, roles) {
      state.roles = roles;
    }
  },
  actions: {
    fetchRoles(context) {
      axios({
        method: 'GET',
        url: '/api/user/getRoles'
      })
        .then((response) => {
          context.commit('setRoles', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getRoles(state) {
      return state.roles;
    }
  }
};