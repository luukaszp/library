import axios from 'axios';

export default {
  state: {
    opinions: []
  },
  mutations: {
    setOpinions(state, opinions) {
      state.opinions = opinions;
    }
  },
  actions: {
    // eslint-disable-next-line camelcase
    fetchOpinions(context, book_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `http://127.0.0.1:8000/api/opinion/all/${book_id}`
      })
        .then((response) => {
          context.commit('setOpinions', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getOpinions(state) {
      return state.opinions;
    }
  }
};