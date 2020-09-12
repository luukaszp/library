<template>
<v-container style="justify-content: center; display: flex">
  <v-card style="width: 1000px">
      <v-row style="justify-content: center; margin-left: 10px; margin-right: 10px" v-for="item in books" :key="item.name">
        <v-col cols="auto">
          <v-img
            height="300"
            width="300"
            :src="('../storage/' + item.cover)"
          ></v-img>
        </v-col>

        <v-divider vertical style="margin-left: 10px; margin-right: 10px"></v-divider>

        <v-col
          cols="auto"
          class="text-center pl-0"
        >
          <v-row
            class="flex-column ma-0 fill-height"
             style="text-align: left;"
          >
            <v-col>
              <h1 v-text="item.title" style="font-weight: bold" class="mr-2"> </h1>
            </v-col>

            <v-col>
              <span>Autor: </span><span style="font-weight: bold" v-text="item.authorName + ' ' + item.surname" class="mr-2"></span>
            </v-col>

            <v-col>
              <span>Kategoria: </span><span style="font-weight: bold" v-text="item.categoryName" class="mr-2"></span>
            </v-col>

            <v-col>
              <span>ISBN: </span><span style="font-weight: bold" v-text="item.isbn" class="mr-2"></span>
            </v-col>

            <v-col>
                <div class="text-center">
              <span>Ocena:
                    <v-rating
                    v-model="rating"
                    background-color="orange lighten-3"
                    color="orange"
                    medium
                    ></v-rating>
                   średnia oceny do 5</span> <span> | </span> <span>Opinie: ilość</span>
                </div>
            </v-col>
          </v-row>
        </v-col>

        <v-divider vertical style="margin-left: 10px; margin-right: 10px"></v-divider>

        <v-col>
            <v-col>
                <span>Opis: </span><span style="font-weight: bold" v-text="item.description" class="mr-2"></span>
            </v-col>
            <v-col>
                <h3>Status: <v-chip :color="getColor(item.amount)" dark>{{ status }}</v-chip></h3>
            </v-col>
        </v-col>
      </v-row>

        <v-divider></v-divider>

      <v-row style="justify-content: center; margin-left: 10px; margin-right: 10px">
          <h2>Oceny i opinie czytelników (ilośc)</h2>
            <v-list two-line>
                <v-list-item-group>
                    <template v-for="(item, index) in items" v-bind:disabled="lockSelection">
                    <v-list-item :key="item.title">
                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                            <v-list-item-subtitle class="text--primary" v-text="item.headline"></v-list-item-subtitle>
                            <v-list-item-subtitle v-text="item.subtitle"></v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-action>
                            <v-list-item-action-text v-text="item.action"></v-list-item-action-text>
                        </v-list-item-action>
                    </v-list-item>

                    <v-divider
                        v-if="index + 1 < items.length"
                        :key="index"
                    ></v-divider>
                    </template>
                </v-list-item-group>
            </v-list>
      </v-row>
  </v-card>
</v-container>
</template>

<script>
/*eslint-disable*/
import axios from 'axios';

export default {
    name: 'BookView',
    props: ['book_id'],
    status: '',
    lockSelection: true,
    data: () => ({
    items: [
        {
          action: '15 min',
          headline: 'Brunch this weekend?',
          title: 'Ali Connors',
          subtitle: "I'll be in your neighborhood doing errands this weekend. Do you want to hang out?",
        },
        {
          action: '2 hr',
          headline: 'Summer BBQ',
          title: 'me, Scrott, Jennifer',
          subtitle: "Wish I could come, but I'm out of town this weekend.",
        },
        {
          action: '6 hr',
          headline: 'Oui oui',
          title: 'Sandra Adams',
          subtitle: 'Do you have Paris recommendations? Have you ever been?',
        },
        {
          action: '12 hr',
          headline: 'Birthday gift',
          title: 'Trevor Hansen',
          subtitle: 'Have any ideas about what we should get Heidi for her birthday?',
        },
        {
          action: '18hr',
          headline: 'Recipe to try',
          title: 'Britta Holt',
          subtitle: 'We should eat this: Grate, Squash, Corn, and tomatillo Tacos.',
        },
      ],
    }),

  computed: {
    books() {
      return this.$store.getters.getBooks;
    }
  },

  created () {
    this.$store.dispatch('fetchOneBook', this.book_id); //lub this.$route.params.book_id
  },

  methods: {
      getColor (amount) {
      if (amount === 0) {
          this.status = 'Brak pozycji!'
          return 'red';
      }
      if (amount <= 5) {
          this.status = 'Ostatnie sztuki!'
          return 'orange';
      }
      this.status = 'Dostępne!'
      return 'green';
    }
  }
};
</script>