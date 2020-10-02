<template>
    <v-row style="justify-content: center; text-align: center">
        <v-col md=5>
            <h1>Ulubieni autorzy</h1>
        </v-col>

        <v-divider vertical></v-divider>

        <v-col md=5>
            <h1>Ulubione książki</h1>
            <v-list-item
                v-for="favBook in this.favouriteBooks"
                :key="favBook.title"
            >
                <v-list-item-content>
                <v-list-item-title v-text="favBook.title"></v-list-item-title>

                <v-list-item-subtitle v-text="favBook.name + ' ' + favBook.surname"></v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-action>
                <v-btn icon>
                    <v-icon color="grey lighten-1" @click="removeBook(favBook)">mdi-close</v-icon>
                </v-btn>
                </v-list-item-action>
            </v-list-item>
        </v-col>
    </v-row>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'Favourites',
  props: ['user_id'],

  data: () => ({
    favouriteBooks: [],
    favouriteAuthors: []
  }),

   async created () {
    /*this.$store.dispatch('fetchOneReader', this.user_id);
    this.$store.dispatch('fetchSpecificDelay', this.user_id);*/
   await this.getFavouriteBooks();
  },

  methods: {
    async getFavouriteBooks() {
		await axios
        .get(`api/favourite/getFavouriteBooks/${this.user_id}`)
        .then((response) => {
            this.favouriteBooks = response.data;
        });
    },

    removeBook(favBook) {
        this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tę książkę z listy ulubionych?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj'
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/favourite/delete/${favBook.id}`, {})
          this.$swal('Usunięto', 'Pomyślnie usunięto książkę z listy ulubionych', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
        this.getFavouriteBooks();
      });
    }
  }
};
</script>