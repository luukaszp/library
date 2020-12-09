<template>
        <div id="app">
                <v-card
                        flat
                        tile
                >
                    <v-app-bar
                            color=#865840
                            dark
                    >
                        <router-link to="/">
                            <v-app-bar-nav-icon>
                                <v-img alt="App logo" :src="require('../assets/app_logo_t.png')" width="90px"/> <!--zmienic logo-->
                            </v-app-bar-nav-icon>
                        </router-link>

                        <v-toolbar-title style="padding-left: 15px;">Biblioteka</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <div class="d-flex justify-center align-center">
                            <v-menu
                            v-for="item in items"
                            :key="item.text"
                            offset-y
                            >
                            <template v-slot:activator="{ attrs, on }">
                                <v-btn
                                outlined
                                class="white--text ma-8"
                                v-bind="attrs"
                                v-on="on"
                                :to="item.route"
                                >
                                {{ item.text }}
                                </v-btn>
                            </template>
                            </v-menu>
                        </div>

                        <v-spacer></v-spacer>

                        <About/>

                        <router-link to="/admin-panel" v-if="loggedUser.id_number">
                            <v-btn icon>
                                <v-icon x-large>mdi-view-dashboard</v-icon>
                            </v-btn>
                        </router-link>

                        <router-link to="/login" v-if="!isLoggedIn">
                            <v-btn icon>
                                <v-icon x-large>mdi-login</v-icon>
                            </v-btn>
                        </router-link>

                        <v-menu offset-y>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" :disabled="!isLoggedIn">
                                    <v-icon>mdi-account</v-icon>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item :to="{ name: 'profile', params: { user_id: authId } }" v-if="loggedUser.card_number">
                                    Profil
                                </v-list-item>
                                <v-list-item>
                                    <span v-if="isLoggedIn"><a @click="logout">Wyloguj się</a></span>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                    </v-app-bar>
                </v-card>
        </div>
</template>

<script>
import About from './About.vue';

export default {
  name: 'Main',
  components: {
    About
  },
  data: () => ({
    items: [
      {
        text: 'KALENDARZ',
        route: '/calendar'
      },
      {
        text: 'PRZEGLĄDAJ',
        route: '/search'
      },
      {
        text: 'NOWOŚCI',
        route: '/new'
      },
      {
        text: 'RANKING',
        route: '/ranking'
      }
    ]
  }),
  computed: {
    isLoggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    loggedUser() {
      return this.$store.getters.loggedUser;
    },
    authId() {
      return this.$store.getters.authId;
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('logout')
        .then(() => this.$router.push({ name: 'login' }));
      const Toast = this.$swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', this.$swal.stopTimer);
          toast.addEventListener('mouseleave', this.$swal.resumeTimer);
        }
      });

      Toast.fire({
        icon: 'success',
        title: 'Wylogowano!'
      });
    }
  }
};
</script>

<style scoped>
    a {
        text-decoration: none;
    }
</style>