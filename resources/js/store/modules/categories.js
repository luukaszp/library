import axios from 'axios'

export default {
    state: {
        categories: []
    },
    mutations: {
        setCategories(state, categories) {
            state.categories = categories
        }
    },
    actions: {
        fetchCategories(context) {   
            axios({
                method: 'GET',
                url: 'http://127.0.0.1:8000/api/category/getCategories'
                })
                .then(response => {
                    context.commit('setCategories', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
    getters : {
        getCategories(state) {
            return state.categories
        }
    },
}