import axios from 'axios'

export default {
    state: {
        status: '',
        token: localStorage.getItem('access_token') || '',
        user: {}
    },

    mutations: {
        auth_request(state){
            state.status = 'loading'
        },
        auth_success(state, token, user){
            state.status = 'success'
            state.token = token
            state.user = user
        },
        auth_error(state){
            state.status = 'error'
        },
        logout(state){
            state.status = ''
            state.token = ''
        }
    },

    actions: {
        userLogin({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('auth_request')
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/login',
                    data: user
                })
                    .then(response => {
                        const token = resp.data.token
                        const user = resp.data.user
                        localStorage.setItem('token', token)
                        axios.defaults.headers['Authorization'] = `Bearer ${token}`
                        commit('auth_success', token, user)
                        resolve(response)
                    })
                    .catch(error => {
                        commit('auth_error')
                        localStorage.removeItem('token')
                        reject(error)
                    })
            })
        },
        userRegister({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('auth_request')
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/register',
                    data: user
                })
                    .then(response => {
                        const token = resp.data.token
                        const user = resp.data.user
                        localStorage.setItem('token', token)
                        axios.defaults.headers['Authorization'] = `Bearer ${token}`
                        commit('auth_success', token, user)
                        resolve(response)
                    })
                    .catch(error => {
                        commit('auth_error')
                        localStorage.removeItem('token')
                        reject(error)
                    })
            })
        },
        logout({commit}) {
            return new Promise((resolve, reject) => {
                commit('logout')
                localStorage.removeItem('token')
                delete axios.defaults.headers['Authorization']
                resolve()
            })
        },
    },

    getters : {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status
    }
}