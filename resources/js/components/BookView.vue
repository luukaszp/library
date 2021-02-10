<template>
<v-container style="justify-content: center; display: flex">
  <v-card style="width: 1000px">
      <v-row style="justify-content: center; margin-left: 10px; margin-right: 10px">
        <v-col cols="auto">
          <v-img
            style="margin-top: 15px; width: 300px; height: 400px"
            :src="('https://storageforlibrary.blob.core.windows.net/library/' + books.cover)"
          ></v-img>
        </v-col>

        <v-divider vertical style="margin-left: 10px; margin-right: 10px"></v-divider>

        <v-col
          cols="3"
          class="text-center pl-0"
        >
          <v-row
            class="flex-column ma-0 fill-height"
             style="text-align: left;"
          >
            <v-col>
              <h1 v-text="books.title" style="font-weight: bold; text-align: center"> </h1>
            </v-col>

            <v-col style="text-align: center">
                <p>Autor: </p>
                <v-list-item-subtitle
                v-for="author in books.authors"
                :key="author.id"
                >
                <router-link v-if="author.id" :to="{ name: 'authorview', params: { author_id: author.id } }" style="text-decoration: none; color: #008D18">
                    <span style="font-weight: bold" v-text="author.name + ' ' + author.surname" class="mr-2"></span>
                </router-link>
                </v-list-item-subtitle>
            </v-col>

            <v-col style="text-align: center">
              <span>Kategoria: </span><span style="font-weight: bold" v-text="books.categories.name" class="mr-2"></span>
            </v-col>

            <v-col>
                <div class="text-center">
              <span>Ocena czytelników: </span>
                    <v-rating
                        v-model="averages.avg"
                        background-color="orange lighten-3"
                        color="orange"
                        medium
                        readonly
                    ></v-rating>
                   {{averages.avg}}/5 <span> | </span> <span>Opinie: {{averages.count}}</span>
                </div>
            </v-col>
          </v-row>
        </v-col>

        <v-divider vertical style="margin-left: 10px; margin-right: 10px"></v-divider>

        <v-col cols="auto">
            <v-col style="padding-top: 20px">
                <span style="font-weight: bold">Opis: </span><span v-text="books.description" class="mr-2"></span>
            </v-col>
            <v-col>
                <h3>Status: <v-chip :color="getColor(books.amount)" dark>{{ status }}</v-chip></h3>
            </v-col>
        </v-col>
      </v-row>

        <v-divider></v-divider>

        <BookRating v-bind:book_id="book_id"/>

  </v-card>
</v-container>
</template>

<script>
/*eslint-disable*/
import BookRating from './BookRating.vue';

export default {
  name: 'BookView',
  props: ['book_id'],
  components: {
    BookRating
  },
  data: () => ({
    status: '',
    rating: 0
  }),

  computed: {
    books() {
      return this.$store.getters.getBooks;
    },
    averages() {
      return this.$store.getters.getAverages;
    }
  },

  created () {
    this.$store.dispatch('fetchOneBook', this.book_id); // lub this.$route.params.book_id
    this.$store.dispatch('fetchAverage', this.book_id);
  },

  methods: {
    getColor (amount) {
      if (amount === 0) {
        this.status = 'Brak pozycji';
        return 'red';
      }
      if (amount === 1 || amount === 2) {
        this.status = 'Ostatnie sztuki!';
        return 'orange';
      }
      this.status = 'Dostępne';
      return 'green';
    }
  }
};
</script>