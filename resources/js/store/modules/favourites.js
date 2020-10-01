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
    fetchFavouriteBooks(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/favourite/getFavouriteBooks'
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