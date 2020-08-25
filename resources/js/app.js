import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import vuetify from './plugins/vuetify';
import router from './router';
import store from './store/store';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/dist/vuetify.min.css';

require('./bootstrap');

axios.defaults.baseURL = 'http://127.0.0.1:8000';

axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('access_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    config.headers['Access-Control-Allow-Origin'] = '*';
    return config;
  },
  (error) => Promise.reject(error)
);

Vue.config.productionTip = false;

Vue.use(VueAxios, axios);

new Vue({
  vuetify,
  router,
  store,
  render: (h) => h(App)
}).$mount('#app');