<template>
        <div id="app">
                <v-card
                    flat
                    tile
                    style="margin-bottom: 15px"
                >
                    <v-app-bar
                        color=#913608
                        dark
                    >
                        <router-link to="/">
                            <v-app-bar-nav-icon>
                                <v-img alt="App logo" :src="require('../assets/app_logo_t.png')" width="90px"/>
                            </v-app-bar-nav-icon>
                        </router-link>

                        <v-toolbar-title class="d-none d-sm-flex" style="padding-left: 15px; font-family: Brush Script MT; font-size: 30px">Biblioteka</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <div class="hidden-sm-and-down justify-center align-center">
                            <v-menu
                            v-for="item in items"
                            :key="item.text"
                            offset-y
                            >
                            <template v-slot:activator="{ attrs, on }">
                                <v-btn
                                outlined
                                rounded
                                class="white--text ma-8 font-weight-bold"
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
                                <v-badge :content="messages" :value="messages" color="#008D18" overlap>
                                    <v-btn icon v-on="on" :disabled="!messages">
                                        <v-icon>mdi-email</v-icon>
                                    </v-btn>
                                </v-badge>
                            </template>
                            <v-card>
                                <v-list
                                dense
                                v-for="notification in notifications"
                                :key="notification.id"
                                style="width: 480px; padding-top: 0px; padding-bottom: 0px"
                                >
                                    <v-list-item-content
                                    v-for="data in notification.data"
                                    :key="data.id"
                                    style="margin-left: 10px"
                                    >
                                    <router-link v-if="data.id" :to="{ name: 'bookview', params: { book_id: data.id } }" style="text-decoration: none; color: #008D18">
                                        <v-subheader v-text="data.message"></v-subheader>
                                        <v-list-item-group>
                                            <v-list-item>
                                            <v-list-item-icon>
                                                <v-icon style="display: inline-block" x-large>mdi-book-open-page-variant</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title style="white-space: normal">Właśnie została dodana książka pod tytułem: <strong>{{data.title}}</strong></v-list-item-title>
                                                <v-list-item-subtitle
                                                v-for="author in data.author"
                                                :key="author.id"
                                                >
                                                Autor: {{author.name}} {{author.surname}}
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                    </router-link>
                                    </v-list-item-content>
                                    <v-divider></v-divider>
                                </v-list>
                                <v-divider></v-divider>
                                <v-card-actions style="justify-content: center">
                                    <v-btn class="white--text" color="#008D18" @click="markAsRead()">
                                        Oznacz jako przeczytane
                                    </v-btn>
                                </v-card-actions>
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
                                    <v-list-item-icon>
                                        <v-icon>mdi-account</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Profil</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon>mdi-logout</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title v-if="isLoggedIn"><a @click="logout">Wyloguj się</a></v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <div class="hidden-sm-and-up justify-center align-center">
                            <v-dialog
                            v-model="dialog"
                            hide-overlay
                            transition="dialog-bottom-transition"
                            >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                color="#913608"
                                v-bind="attrs"
                                v-on="on"
                                >
                                <v-icon>mdi-hamburger</v-icon>
                                </v-btn>
                            </template>
                            <v-card>
                                <v-toolbar
                                dark
                                color="#913608"
                                >
                                <v-btn
                                    icon
                                    dark
                                    @click="dialog = false"
                                >
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                                <v-toolbar-title>MENU</v-toolbar-title>
                                <v-spacer></v-spacer>
                                </v-toolbar>
                                <v-list
                                v-for="item in items"
                                :key="item.text"
                                :prepend-icon="item.action"
                                >
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-list-item :to="item.route">
                                            <v-icon>{{ item.action }}</v-icon>
                                            {{ item.text }}
                                        </v-list-item>
                                    </v-list-item-content>
                                </v-list-item>
                                </v-list>
                            </v-card>
                            </v-dialog>
                        </div>

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
    dialog: false,
    items: [
      {
        action: 'mdi-calendar-check',
        text: 'KALENDARZ',
        route: '/calendar'
      },
      {
        action: 'mdi-magnify',
        text: 'PRZEGLĄDAJ',
        route: '/search'
      },
      {
        action: 'mdi-new-box',
        text: 'NOWOŚCI',
        route: '/new'
      },
      {
        action: 'mdi-trophy',
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

    .v-menu__content {
        max-height: 280px;
    }
    @media only screen and (max-width: 600px) {
        .spacer {
            display: none;
        }
    }
</style>
