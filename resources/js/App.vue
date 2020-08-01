<template>
  <v-app>
      <app-header v-if="token != null" :isLogged="isLogged"></app-header>
      <Header/>

      <v-content>
          <router-view></router-view>
      </v-content>

      <Footer/>

  </v-app>
</template>

<script>
    import Header from './components/Header';
    import Footer from './components/Footer';
    export default {
        name: 'App',
        components: {
            'app-header': Header,
            Footer
        },
        data() {
            return {
                isLogged: localStorage.getItem("token"),
                visible: false
            };
        },
        computed: {
            token() {
                return this.$store.getters.loggedIn;
            }
        }
    };
</script>