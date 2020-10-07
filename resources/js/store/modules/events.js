import axios from 'axios';

export default {
  state: {
    events: []
  },
  mutations: {
    setEvents(state, events) {
      state.events = events;
    }
  },
  actions: {
    fetchEvents(context) {
      axios({
        method: 'GET',
        url: 'http://127.0.0.1:8000/api/calendar/event/getEvents'
      })
        .then((response) => {
          context.commit('setEvents', response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  getters: {
    getEvents(state) {
      return state.events;
    }
  }
};