import axios from 'axios';

export default {
  state: {
    authors: []
  },
  mutations: {
    setAuthors(state, authors) {
      state.authors = authors;
    }
  },
  actions: {
    fetchAuthors(context) {
      axios({
        method: 'GET',
        url: '/api/author/getAuthors'
      })
        .then((response) => {
          context.commit('setAuthors', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    // eslint-disable-next-line camelcase
    fetchOneAuthor(context, author_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `/api/author/${author_id}`
      })
        .then((response) => {
          context.commit('setAuthors', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getAuthors(state) {
      return state.authors;
    }
  }
};