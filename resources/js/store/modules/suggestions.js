import axios from 'axios';

export default {
  state: {
    suggestions: []
  },
  mutations: {
    setSuggestions(state, suggestions) {
      state.suggestions = suggestions;
    }
  },
  actions: {
    fetchSuggestions(context) {
      axios({
        method: 'GET',
        url: '/api/suggestions/getSuggestions'
      })
        .then((response) => {
          context.commit('setSuggestions', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getSuggestions(state) {
      return state.suggestions;
    }
  }
};