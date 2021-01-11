import axios from 'axios';

export default {
  state: {
    publishers: []
  },
  mutations: {
    setPublishers(state, publishers) {
      state.publishers = publishers;
    }
  },
  actions: {
    fetchPublishers(context) {
      axios({
        method: 'GET',
        url: '/api/publisher/getPublishers'
      })
        .then((response) => {
          context.commit('setPublishers', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getPublishers(state) {
      return state.publishers;
    }
  }
};