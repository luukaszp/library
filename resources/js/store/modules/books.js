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
    }

  },
  getters: {
    getBooks(state) {
      return state.books;
    }
  }
};