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
              <h1 v-text="item.title" style="font-weight: bold; text-align: center" class="mr-2"> </h1>
            </v-col>

            <v-col>
              <span>Autor: </span><span style="font-weight: bold" v-text="item.authorName + ' ' + item.surname" class="mr-2"></span>
            </v-col>

            <v-col>
              <span>Kategoria: </span><span style="font-weight: bold" v-text="item.categoryName" class="mr-2"></span>
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

        <v-col>
            <v-col>
                <span style="font-weight: bold">Opis: </span><span v-text="item.description" class="mr-2"></span>
            </v-col>
            <v-col>
                <h3>Status: <v-chip :color="getColor(item.amount)" dark>{{ status }}</v-chip></h3>
            </v-col>
            <v-col>
                <v-btn
                outlined
                color="indigo"
                @click="addFavourite()"
                >
                    DODAJ DO ULUBIONYCH
                    <v-icon style="padding-left: 10px" color="#FFD700">mdi-star</v-icon>
                </v-btn>
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
import axios from 'axios';
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
    },
    authId() {
      return this.$store.getters.authId;
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
      if (amount <= 5) {
        this.status = 'Ostatnie sztuki!';
        return 'orange';
      }
      this.status = 'Dostępne';
      return 'green';
    },

    addFavourite() {
      axios.post('/api/favourite/addBook', {
        user_id: this.authId,
        book_id: parseInt(this.book_id)
      })
        .catch((error) => {
          console.log(error);
        });
    }
  }
};
</script>