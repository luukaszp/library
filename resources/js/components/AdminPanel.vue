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
            <h1>Test</h1>
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
                    { title: 'Pracownicy' },
                ],
            },
            {
                action: 'mdi-account',
                title: 'Książki',
                items: [
                    { title: 'Ogólne zestawienie' },
                    { title: 'Autorzy' },
                    { title: 'Kategorie' },
                    { title: 'Wydawnictwa' },
                    { title: 'Tytuły' },
                ],
            },
            {
                action: 'mdi-account',
                title: 'Wypożyczenia i oddania',
                items: [
                    { title: 'Przeglądaj' },
                    { title: 'Wypożyczone' },
                    { title: 'Opóźnienia i kary' },
                ],
            },
            {
                action: 'mdi-account',
                title: 'Akcje',
                items: [
                    { title: 'Propozycje czytelników' },
                    { title: 'Opinie' },
                    { title: 'Wypełnione ankiety' },
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