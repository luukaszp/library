import axios from 'axios';

export default {
  state: {
    readers: []
  },
  mutations: {
    setReaders(state, readers) {
      state.readers = readers;
    }
  },
  actions: {
    fetchReaders(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/user/getReaders'
      })
        .then((response) => {
          context.commit('setReaders', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    // eslint-disable-next-line camelcase
    fetchOneReader(context, user_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `http://127.0.0.1:8000/api/user/profile/${user_id}`
      })
        .then((response) => {
          context.commit('setReaders', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getReaders(state) {
      return state.readers;
    }
  }
};