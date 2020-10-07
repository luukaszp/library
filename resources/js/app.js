import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VueSweetalert2 from 'vue-sweetalert2';
import VueApexCharts from 'vue-apexcharts';
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

/* axios.interceptors.response.use((response) => response,
  (error) => {
    if (error.response.status !== 401) {
      return Promise.reject(error);
    }
    if (error.response.status === 401) {
      axios({
        method: 'POST',
        url: 'http://127.0.0.1:8000/api/refresh',
        data: {
          access_token: localStorage.getItem('access_token')
        }
      })
        .then((response) => {
          if (response.status === 201) {
            localStorage.removeItem('access_token');
            // 1) put token to LocalStorage
            const token = localStorage.setItem('access_token', response.data.access_token);

            // 2) Change Authorization header
            axios.defaults.headers.Authorization = `Bearer ${token}`;
            axios.defaults.headers['Access-Control-Allow-Origin'] = '*';
            // window.location.reload();
          }
        });
    }
  }); tutaj miał być warunek, czy chce się ponownie zalogować czy też nie, ale to .js */

axios.interceptors.response.use((response) => response,
  // eslint-disable-next-line consistent-return
  (error) => {
    if (error.response.status !== 401) {
      return Promise.reject(error);
    }
    if (error.response.data.message === 'Token has expired' && localStorage.access_token != null) {
      localStorage.removeItem('access_token');
      Vue.swal({
        title: 'Sesja wygasła',
        text: 'Za chwilę zostaniesz wylogowany',
        icon: 'warning',
        confirmButtonText: 'Rozumiem'
      }).then((result) => {
        if (result.value) {
          Vue.swal('Wylogowano', 'Pomyślnie wylogowano!', 'success');
          delete axios.defaults.headers.Authorization;
          router.push('/login');
        }
      });
    }
  });

Vue.config.productionTip = false;

Vue.use(VueSweetalert2);
Vue.use(VueApexCharts);
Vue.use(VueAxios, axios);
Vue.component('apexchart', VueApexCharts);

new Vue({
  vuetify,
  router,
  store,
  render: (h) => h(App)
}).$mount('#app');