import axios from 'axios'

export default {
    state: {
        workers: []
    },
    mutations: {
        setWorkers(state, workers) {
            state.workers = workers
        }
    },
    actions: {
        fetchWorkers(context) {   
            axios({
                method: 'GET',
                url: 'http://127.0.0.1:8000/api/user/getWorkers'
                })
                .then(response => {
                    context.commit('setWorkers', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
    getters : {
        getWorkers(state) {
            return state.workers
        }
    },
}