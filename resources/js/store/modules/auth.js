import axios from 'axios'

export default {
    state: {
        token: localStorage.getItem('access_token') || null,
    },

    mutations: {
        retrieveToken(state, token) {
            state.token = token
        },
        destroyToken(state) {
            state.token = null
        },
        setNotifications(state, notifications) {
            state.notifications = notifications
        }
    },

    actions: {
        getToken(context, credentials) {
            return new Promise((resolve, reject) => {
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/login',
                    data: {
                        email: credentials.email,
                        password: credentials.password,
                    }
                })
                    .then(response => {
                        const token = response.data.payload.accessToken
                        localStorage.setItem('access_token', token)
                        axios.defaults.headers['Authorization'] = `Bearer ${token}`
                        context.commit('retrieveToken', token)
                        router.push('/')
                    })
                    .catch(error => {
                        console.log(error)
                    })
            })
        },
        userRegister(context, credentials) {
            return new Promise((resolve, reject) => {
                axios({
                    method: 'POST',
                    url: 'http://127.0.0.1:8000/api/register',
                    data: {
                        name: credentials.name,
                        surname: credentials.surname,
                        email: credentials.email,
                        card_number: credentials.card_number,
                        id_number: credentials.id_number,
                        password: credentials.password,
                        password_confirmation: credentials.password_confirmation
                    }
                })
                .then(response => {
                    if(response.data.success == true)
                    {
                        alert("Zarejestrowano pomyślnie!")
                    }
                    else
                    {
                        alert("Użytkownik o podanym emailu już istnieje.")
                    }
                })
                .catch(error => {
                    console.log(error)
                })
            })
        },
        destroyToken(context) {
            if (context.getters.loggedIn) {
                    context.commit('destroyToken')
                    localStorage.removeItem('access_token')
            }
        },
    },

    getters : {
        loggedIn(state) {
            return state.token !== null
        }
    },
}