import axios from 'axios';

export default {
  state: {
    books: []
  },
  mutations: {
    setBooks(state, books) {
      state.books = books;
    }
  },
  actions: {
    fetchBooks(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/book/getBooks'
      })
        .then((response) => {
          context.commit('setBooks', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    fetchAvailableBooks(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/book/getAvailableBooks'
      })
        .then((response) => {
          context.commit('setBooks', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    // eslint-disable-next-line camelcase
    fetchOneBook(context, book_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `http://127.0.0.1:8000/api/book/${book_id}`
      })
        .then((response) => {
          context.commit('setBooks', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }

  },
  getters: {
    getBooks(state) {
      return state.books;
    }
  }
};