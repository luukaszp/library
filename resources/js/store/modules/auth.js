/* eslint-disable */
import axios from 'axios';
import jwt_decode from 'jwt-decode';
import router from '../../router';

export default {
  state: {
    status: '',
    token: localStorage.getItem('access_token') || '',
    loggedUser: {
      id: '',
      is_admin: '',
      id_number: '',
      card_number: ''
    }
  },

  mutations: {
    auth_request(state) {
      state.status = 'loading';
    },
    auth_success(state, token) {
      state.status = 'success';
      state.token = token;
      state.loggedUser = {
        id: jwt_decode(token).user_id,
        is_admin: jwt_decode(token).is_admin,
        id_number: jwt_decode(token).id_number,
        card_number: jwt_decode(token).card_number
      };
    },
    auth_error(state) {
      state.status = 'error';
    },
    logout(state) {
      state.status = '';
      state.token = '';
      state.loggedUser = {
        id: null,
        is_admin: null,
        id_number: null,
        card_number: null
      };
    }
  },

  actions: {
    login({ commit }, user) {
      return new Promise((resolve, reject) => {
        commit('auth_request');
        if (user.login.length === 10) {
          axios({
            method: 'POST',
            url: 'http://127.0.0.1:8000/api/loginReader',
            data: {
              card_number: user.login,
              password: user.password
            }
          })
            .then((response) => {
              const token = response.data.access_token;
              user = response.data;
              localStorage.setItem('access_token', token);
              axios.defaults.headers.Authorization = `Bearer ${token}`;
              commit('auth_success', token);
              router.push('/');
              resolve(response);
            })
            .catch((error) => {
              commit('auth_error');
              localStorage.removeItem('access_token');
              reject(error);
            });
        } else {
          axios({
            method: 'POST',
            url: 'http://127.0.0.1:8000/api/loginWorker',
            data: {
              id_number: user.login,
              password: user.password
            }
          })
            .then((response) => {
              const token = response.data.access_token;
              user = response.data;
              localStorage.setItem('access_token', token);
              axios.defaults.headers.Authorization = `Bearer ${token}`;
              commit('auth_success', token, user);
              router.push('/');
              resolve(response);
            })
            .catch((error) => {
              commit('auth_error');
              localStorage.removeItem('access_token');
              reject(error);
            });
        }
      });
    },
    userRegister({ commit }, user) {
      return new Promise((resolve, reject) => {
        commit('auth_request');
        axios({
          method: 'POST',
          url: 'http://127.0.0.1:8000/api/register',
          data: user
        })
          .then((response) => {
            if (response.data.success === true) {
              if (user.id_number === '') {
                router.push('/admin-panel/readers');
              } else {
                router.push('/admin-panel/workers');
              }
            } else {
              alert('Użytkownik o podanym loginie już istnieje!');
            }
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    logout({ commit }) {
      return new Promise((resolve) => {
        commit('logout');
        localStorage.removeItem('access_token');
        delete axios.defaults.headers.Authorization;
        resolve();
      });
    },
    firstLogin({ commit }, user) {
        return new Promise((resolve, reject) => {
          commit('auth_request');
          axios({
            method: 'POST',
            url: 'http://127.0.0.1:8000/api/first-login-password',
            data: user
          })
            .then((response) => {
              if (response.data.success === true) {
                alert('Pomyślnie zmieniono hasło! Zaloguj się ponownie!');
                localStorage.removeItem('access_token');
                delete axios.defaults.headers.Authorization;
                router.push('/login');
              } else {
                alert('Coś poszło nie tak!');
              }
              resolve(response);
            })
            .catch((error) => {
              reject(error);
            });
        });
      },
  },

  getters: {
    isLoggedIn: (state) => state.token,
    authStatus: (state) => state.status,
    loggedUser: (state) => state.loggedUser,
    authId: (state) =>state.loggedUser.id
  }
};