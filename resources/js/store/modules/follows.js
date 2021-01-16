import axios from 'axios';

export default {
  state: {
    follows: []
  },
  mutations: {
    setFollows(state, follows) {
      state.follows = follows;
    }
  },
  actions: {
    // eslint-disable-next-line camelcase
    fetchFollowedAuthors(context, reader_id) {
      axios({
        method: 'GET',
        // eslint-disable-next-line camelcase
        url: `/api/follow/getFollowedAuthors/${reader_id}`
      })
        .then((response) => {
          context.commit('setFollows', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getFollows(state) {
      return state.follows;
    }
  }
};