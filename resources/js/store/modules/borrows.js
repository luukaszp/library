/* eslint-disable */
import axios from 'axios';
import router from '../../router';

export default {
  state: {
    borrows: []
  },
  mutations: {
    setBorrows(state, borrows) {
      state.borrows = borrows;
    }
  },
  actions: {
    borrowBooks(context, credentials) {
      return new Promise((resolve, reject) => {
        axios({
          method: 'POST',
          url: 'http://127.0.0.1:8000/api/borrow/addBorrow',
          data: {
            reader_id: credentials.reader_id,
            book_id: credentials.book_id,
            borrows_date: credentials.borrows_date,
            isbn: credentials.isbn
          }
        })
          .then((response) => {
            if (response.data.success === true) {
              router.push('/admin-panel/borrows');
            }
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },

    fetchBorrows(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/borrow/getBorrows'
      })
        .then((response) => {
          context.commit('setBorrows', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    fetchDelays(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/borrow/getDelayed'
      })
        .then((response) => {
          context.commit('setBorrows', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    fetchHistory(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/borrow/history'
      })
        .then((response) => {
          context.commit('setBorrows', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    // eslint-disable-next-line camelcase
    fetchSpecificBorrow(context, user_id) {
        axios({
          method: 'GET',
          // eslint-disable-next-line camelcase
          url: `http://127.0.0.1:8000/api/borrow/showBorrow/${user_id}`
        })
          .then((response) => {
            context.commit('setBorrows', response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      },

      // eslint-disable-next-line camelcase
    fetchSpecificDelay(context, user_id) {
        axios({
          method: 'GET',
          // eslint-disable-next-line camelcase
          url: `http://127.0.0.1:8000/api/borrow/showDelay/${user_id}`
        })
          .then((response) => {
            context.commit('setBorrows', response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      },

      fetchMonth(context) {
        axios({
          method: 'GET',
          url: 'http://127.0.0.1:8000/api/borrow/monthly'
        })
          .then((response) => {
            context.commit('setBorrows', response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      },
  },
  getters: {
    getBorrows(state) {
      return state.borrows;
    }
  }
};