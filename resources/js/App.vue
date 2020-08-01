<template>
  <v-app>
      <!--<app-header v-if="token != null" :isLogged="isLogged"></app-header>
    --><Header/>
      <v-main>
          <router-view></router-view>
      </v-main>

      <Footer/>

  </v-app>
</template>

<script>
    import Header from './components/Header';
    import Footer from './components/Footer';
    export default {
        name: 'App',
        components: {
            Header,
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