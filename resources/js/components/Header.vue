<template>
        <div id="app">
                <v-card
                        flat
                        tile
                >
                    <v-app-bar
                            color=#4E1D04
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

                        <v-menu offset-y v-if="loggedUser.card_number">
                            <template v-slot:activator="{ on }">
                                <v-badge :content="messages" :value="messages" color="green" overlap>
                                    <v-btn icon v-on="on" :disabled="!messages">
                                        <v-icon>mdi-email</v-icon>
                                    </v-btn>
                                </v-badge>
                            </template>
                            <v-card>
                                <v-list
                                v-for="notification in notifications"
                                :key="notification.id"
                                >
                                    <v-list-item-content
                                    v-for="data in notification.data"
                                    :key="data.id"
                                    style="margin-left: 10px"
                                >
                                        <v-subheader v-text="data.message"></v-subheader>
                                        <v-list-item-title>Właśnie została dodana książka pod tytułem: {{data.title}}</v-list-item-title>
                                            <v-list-item-subtitle
                                            v-for="author in data.author"
                                            :key="author.id"
                                            >
                                            Autor: {{author.name}} {{author.surname}}
                                            </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list>
                                <v-btn color="success" @click="markAsRead()">
                                    Oznacz jako przeczytane
                                </v-btn>
                            </v-card>
                        </v-menu>

                        <About/>

                        <router-link to="/admin-panel" v-if="loggedUser.id_number">
                            <v-btn icon>
                                <v-icon large>mdi-view-dashboard</v-icon>
                            </v-btn>
                        </router-link>

                        <router-link to="/login" v-if="!isLoggedIn">
                            <v-btn icon>
                                <v-icon large>mdi-login</v-icon>
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
import axios from 'axios';
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
    ],
    notifications: []
  }),
  computed: {
    isLoggedIn() {
      if (this.$store.getters.isLoggedIn.length !== 0) {
        this.getNotifications();
      }
      return this.$store.getters.isLoggedIn;
    },
    loggedUser() {
      return this.$store.getters.loggedUser;
    },
    authId() {
      return this.$store.getters.authId;
    },
    messages() {
      let messages = 0;
      // eslint-disable-next-line no-restricted-syntax
      for (const key in this.notifications) {
        if (this.notifications[key] != null) {
          messages++;
        }
      }
      return messages;
    }
  },

  methods: {
    getNotifications() {
      axios
        .get(`/api/notifications/${this.authId}`)
        .then((response) => {
          this.notifications = response.data;
        });
    },

    markAsRead() {
      axios
        .get(`/api/notifications/read/${this.authId}`);
      this.notifications = null;
    },

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
      this.notifications = null;
    }
  }
};
</script>

<style scoped>
    a {
        text-decoration: none;
    }
</style>