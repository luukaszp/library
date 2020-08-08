<template>
    <v-row style="height: 100%">
        <v-col md="3" style="padding-top: 0px; padding-bottom: 0px">
            <v-card
            class="mx-auto"
            style="height: 100%; border-radius: 0px"
        >
            <!--mini-variant-width zamieniony na width--><v-navigation-drawer
            permanent
            width="320px"
            dark
            >
            <template v-slot:prepend>
                <v-list-item two-line>
                <v-list-item-avatar>
                    <img src="https://randomuser.me/api/portraits/women/81.jpg">
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>Biblioteka</v-list-item-title>
                    <v-list-item-subtitle>Panel zarządzania</v-list-item-subtitle>
                </v-list-item-content>
                </v-list-item>
            </template>

            <v-divider></v-divider>

            <v-list dense>
                <v-list-group
                    v-for="item in items"
                    :key="item.title"
                    v-model="item.active"
                    :prepend-icon="item.action"
                    no-action
                >
                    <template v-slot:activator>
                    <v-list-item-content>
                        <v-list-item-title v-text="item.title"></v-list-item-title>
                    </v-list-item-content>
                    </template>

                    <v-list-item
                    v-for="subItem in item.items"
                    :key="subItem.title"
                    :to="subItem.route"
                    >
                    <v-list-item-content>
                        <v-list-item-title v-text="subItem.title"></v-list-item-title>
                    </v-list-item-content>
                    </v-list-item>
                </v-list-group>
            </v-list>
            </v-navigation-drawer>
        </v-card>
        </v-col>
        <v-col>
            <router-view></router-view>
        </v-col>
    </v-row>
</template>

<script>
import Readers from "../components/Readers.vue";

  export default {
    data () {
      return {
        items: [
            {
                action: 'mdi-account-group-outline',
                title: 'Użytkownicy',
                items: [
                    { title: 'Czytelnicy', route: '/admin-panel/readers' },
                    { title: 'Pracownicy', route: '/admin-panel/workers' },
                ],
            },
            {
                action: 'mdi-account',
                title: 'Książki',
                items: [
                    { title: 'Ogólne zestawienie', route: '/admin-panel/books' },
                    { title: 'Autorzy', route: '/admin-panel/authors' },
                    { title: 'Kategorie', route: '/admin-panel/categories' },
                    { title: 'Wydawnictwa', route: '/admin-panel/publishers' },
                    { title: 'Tytuły', route: '/admin-panel/titles' },
                ],
            },
            {
                action: 'mdi-account',
                title: 'Wypożyczenia i oddania',
                items: [
                    { title: 'Przeglądaj', route: '/admin-panel/borrows-returns' },
                    { title: 'Wypożyczone', route: '/admin-panel/borrows' },
                    { title: 'Opóźnienia i kary', route: '/admin-panel/delays-penalties' },
                ],
            },
            {
                action: 'mdi-account',
                title: 'Akcje',
                items: [
                    { title: 'Propozycje czytelników', route: '/admin-panel/suggestions' },
                    { title: 'Opinie', route: '/admin-panel/opinions' },
                    { title: 'Wypełnione ankiety', route: '/admin-panel/userforms' },
                ],
            },
        ]
      }
    },
  }
</script>

<style scoped>
    a {
        text-decoration: none;
    }

</style>