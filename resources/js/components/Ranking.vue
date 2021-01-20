<template>
    <v-container>
        <v-card>
            <v-row style="justify-content: center">
                <v-card-title>Ranking oraz statystyki na miesiąc '<strong>{{ new Date().toLocaleString('default', { month: 'long' }) }} </strong>'</v-card-title>
            </v-row>
            <v-row style="justify-content: center">
                <v-col cols=4>
                    <v-img
                    width="600px"
                    :src="require('../assets/ranking/winners_podium.jpg')"
                    height="300px"
                    dark
                    >
                    </v-img>
                </v-col>
            </v-row>
            <v-divider></v-divider>
        </v-card>
        <v-card>
            <v-row style="justify-content: center">
            <v-col cols="4">
                <v-list>
                    <v-toolbar-title style="text-align: center"><strong>Wypożyczenia</strong></v-toolbar-title>
                    <v-subheader style="justify-content: space-between"><p>Pozycja</p><p>Ilość wypożyczeń</p></v-subheader>
                    <v-list-item-group>
                        <template v-for="(item, index) in borrows">
                            <v-list-item :key="item.id">
                                <v-list-item-icon v-if="item.status === 'up'">
                                    <v-icon color="green">mdi-arrow-up-bold</v-icon>
                                    <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                                </v-list-item-icon>

                                <v-list-item-icon v-if="item.status === 'down'">
                                    <v-icon color="red">mdi-arrow-down-bold</v-icon>
                                    <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                                </v-list-item-icon>

                                <v-list-item-icon v-if="item.status === 'same'">
                                    <v-icon color="blue">mdi-minus</v-icon>
                                    <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                                </v-list-item-icon>

                                <v-list-item-icon v-if="item.status === 'new'">
                                    <v-icon color="yellow">mdi-star</v-icon>
                                    <strong style="margin-top: 3px; margin-left: 5px" v-text="item.position"></strong>
                                </v-list-item-icon>

                                <v-list-item-avatar>
                                    <v-img :src="'../storage/' + item.avatar"></v-img>
                                </v-list-item-avatar>

                                <v-list-item-content style="padding-left: 10px">
                                    <v-list-item-title v-text="item.name + ' ' + item.surname"></v-list-item-title>
                                    <v-list-item-subtitle v-html="'Miejsce '+ ++index"></v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-list-item-title v-text="item.count"></v-list-item-title>
                                </v-list-item-action>
                            </v-list-item>
                        </template>
                    </v-list-item-group>
                </v-list>
            </v-col>
            <v-divider vertical></v-divider>
            <v-col cols="3" style="margin-left: 25px; margin-right: 25px">
                <v-toolbar-title style="text-align: center; padding-bottom: 10px">W tym miesiącu dołączyło</v-toolbar-title>
                <h1 style="text-align: center; color: #008D18"><strong>{{readers}}</strong></h1>
                <v-toolbar-title style="text-align: center; padding-top: 10px">czytelników</v-toolbar-title>
                <v-divider></v-divider>
                <v-toolbar-title style="text-align: center; padding-bottom: 10px">W tym miesiącu dodano</v-toolbar-title>
                <h1 style="text-align: center; color: #008D18"><strong>{{books}}</strong></h1>
                <v-toolbar-title style="text-align: center; padding-top: 10px">nowych książek</v-toolbar-title>
            </v-col>
            <v-divider vertical></v-divider>
            <v-col cols="4">
                <v-list>
                    <v-toolbar-title style="text-align: center"><strong>Recenzje</strong></v-toolbar-title>
                    <v-subheader style="justify-content: end"><p>Ilość recenzji</p></v-subheader>
                    <v-list-item-group>
                        <template v-for="(item, index) in ratings">
                            <v-list-item :key="item.id">
                                <v-list-item-avatar>
                                    <v-img :src="'../storage/' + item.avatar"></v-img>
                                </v-list-item-avatar>

                                <v-list-item-content style="padding-left: 10px">
                                    <v-list-item-title v-text="item.name + ' ' + item.surname"></v-list-item-title>
                                    <v-list-item-subtitle v-html="'Miejsce '+ ++index"></v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-list-item-title v-text="item.count"></v-list-item-title>
                                </v-list-item-action>
                            </v-list-item>
                        </template>
                    </v-list-item-group>
                </v-list>
            </v-col>
            </v-row>
        </v-card>
    </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data: () => ({
    books: []
  }),

  computed: {
    borrows() {
      return this.$store.getters.getBorrows;
    },
    ratings() {
      return this.$store.getters.getRatings;
    },
    readers() {
      return this.$store.getters.getReaders;
    }
  },

  created () {
    this.$store.dispatch('fetchMonth', {});
    this.$store.dispatch('fetchMonthRating', {});
    this.$store.dispatch('fetchMonthReaders', {});
  },

  methods: {
    setData(books) {
      this.books = books;
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
      .get('/api/book/monthly/get/all')
      .then((response) => {
        next((vm) => vm.setData(response.data));
      });
  }
};
</script>