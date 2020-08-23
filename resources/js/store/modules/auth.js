import axios from 'axios'
import router from '../../router'
import jwt_decode from 'jwt-decode'

export default {
    state: {
        status: '',
        token: localStorage.getItem('access_token') || '',
        loggedUser: {
            id: '',
            is_worker: '',
            is_admin: ''
        }
    },

    mutations: {
        auth_request(state) {
            state.status = 'loading'
        },
        auth_success(state, token, user) {
            state.status = 'success'
            state.token = token
            state.loggedUser = {
                id: jwt_decode(token).sub,
                is_worker: jwt_decode(token).is_worker,
                is_admin: jwt_decode(token).is_admin
            };
        },
        auth_error(state) {
            state.status = 'error'
        },
        logout(state) {
            state.status = ''
            state.token = ''
        }
    },

    actions: {
        login({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('auth_request')
                if (user.login.length === 10) {
                    axios({
                        method: 'POST',
                        url: 'http://127.0.0.1:8000/api/loginReader',
                        data: {
                            card_number: user.login,
                            password: user.password
                        }
                    })
                    .then(response => {
                        const token = response.data.access_token
                        const user = response.data.user
                        localStorage.setItem('access_token', token)
                        axios.defaults.headers['Authorization'] = `Bearer ${token}`
                        commit('auth_success', token, user)
                        router.push('/')
                    })
                    .catch(error => {
                        commit('auth_error')
                        localStorage.removeItem('access_token')
                        console.log(error)
                    })
                } else {
                    axios({
                        method: 'POST',
                        url: 'http://127.0.0.1:8000/api/loginWorker',
                        data: {
                            id_number: user.login,
                            password: user.password
                        }
                    })
                    .then(response => {
                        const token = response.data.access_token
                        const user = response.data.user
                        localStorage.setItem('access_token', token)
                        axios.defaults.headers['Authorization'] = `Bearer ${token}`
                        commit('auth_success', token, user)
                        router.push('/')
                    })
                    .catch(error => {
                        commit('auth_error')
                        localStorage.removeItem('access_token')
                        console.log(error)
                    })
                }
            })
        },
        userRegister({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('auth_request')
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/api/register',
                    data: user
                })
                .then(response => {
                    if(response.data.success == true) {
                        const token = response.data.token
                        const user = response.data.user
                        localStorage.setItem('access_token', token)
                        axios.defaults.headers['Authorization'] = `Bearer ${token}`
                        commit('auth_success', token, user)

                        alert("Zarejestrowano pomyślnie!")
                        if(credentials.id_number == '') {
                            router.push('/admin-panel/readers')
                        }
                        else {
                            router.push('/admin-panel/workers')
                        }
                    }
                    else {
                        alert("Użytkownik o podanym emailu już istnieje.")
                    }
                })
                .catch(error => {
                    commit('auth_error')
                    localStorage.removeItem('access_token')
                    console.log(error)
                })
            })
        },
        logout({commit}) {
            return new Promise((resolve) => {
                commit('logout')
                localStorage.removeItem('access_token')
                delete axios.defaults.headers['Authorization']
                resolve()
            })
        },
},

    getters : {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
        loggedUser: state => state.loggedUser,
    },
}