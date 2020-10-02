import axios from 'axios';

export default {
  state: {
    favourites: []
  },
  mutations: {
    setFavourites(state, favourites) {
      state.favourites = favourites;
    }
  },
  actions: {
    // eslint-disable-next-line camelcase
    fetchFavouriteBooks(context, user_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `http://127.0.0.1:8000/api/favourite/getFavouriteBooks/${user_id}`
      })
        .then((response) => {
          context.commit('setFavourites', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getFavourites(state) {
      return state.favourites;
    }
  }
};