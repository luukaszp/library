import axios from 'axios';

export default {
  state: {
    averages: []
  },
  mutations: {
    setAverages(state, averages) {
      state.averages = averages;
    }
  },
  actions: {
    // eslint-disable-next-line camelcase
    fetchAverage(context, book_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `http://127.0.0.1:8000/api/rating/${book_id}`
      })
        .then((response) => {
          context.commit('setAverages', response.data.ratings);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getAverages(state) {
      return state.averages;
    }
  }
};