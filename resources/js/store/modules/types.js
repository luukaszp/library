import axios from 'axios';

export default {
  state: {
    types: []
  },
  mutations: {
    setTypes(state, types) {
      state.types = types;
    }
  },
  actions: {
    fetchTypes(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/calendar/type/getTypes'
      })
        .then((response) => {
          context.commit('setTypes', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getTypes(state) {
      return state.types;
    }
  }
};