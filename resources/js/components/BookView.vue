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
              <span>Ocena czytelników: </span>
                    <v-rating
                        v-model="rating"
                        background-color="orange lighten-3"
                        color="orange"
                        medium
                        readonly
                    ></v-rating>
                   średnia oceny do 5 <span> | </span> <span>Opinie: ilość</span>
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

        <BookRating/>
  </v-card>
</v-container>
</template>

<script>
/*eslint-disable*/
import axios from 'axios';
import BookRating from './BookRating.vue'

export default {
    name: 'BookView',
    props: ['book_id'],
    status: '',
    rating: '',
    components: {
        SongRating
    },
    data: () => ({

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