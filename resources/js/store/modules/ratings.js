import axios from 'axios';

export default {
  state: {
    ratings: []
  },
  mutations: {
    setRatings(state, ratings) {
      state.ratings = ratings;
    }
  },
  actions: {
    // eslint-disable-next-line camelcase
    fetchRatings(context, book_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `/api/rating/all/${book_id}`
      })
        .then((response) => {
          context.commit('setRatings', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getRatings(state) {
      return state.ratings;
    }
  }
};