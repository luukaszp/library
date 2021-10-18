<template>
    <v-container>
        <h1 style="text-align: center; padding-bottom: 10px">Książki tego autora:</h1>
        <v-row style="justify-content: space-around; text-align: center">
            <v-col
                v-for="item in books"
                :key="item.name"
                cols="10"
                sm="6"
                md="4"
                lg="3"
                style="display: flex; justify-content: center"
            >
                <v-card class="card fill-height">
                    <v-card-title style="justify-content: center; padding-top: 30px">
                        <span v-text="item.title"></span>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-img
                        style="width: 300px; height: 400px; display: inline-block"
                        v-bind:src="('https://library-site.s3.eu-north-1.amazonaws.com/covers/' + item.cover)"
                    >
                    </v-img>

                    <v-divider></v-divider>

                    <v-card-text style="justify-content: center; padding-top: 5px">
                        <p>Kategoria: <span v-text="item.categoryName" class="mr-2"></span></p>
                        <p>Wydawnictwo: <span v-text="item.publisherName" class="mr-2"></span></p>
                        <p>Opis: <span v-text="item.description" class="mr-2"></span></p>
                        <p>Rok wydania: <span v-text="item.publish_year" class="mr-2"></span></p>
                        <v-divider></v-divider>
                        <router-link :to="{ name: 'bookview', params: { book_id: item.id } }"><v-btn outlined style="border: 0px; text-decoration: none"><v-card-title style="color: #008D18; font-weight: bold">Zobacz więcej</v-card-title></v-btn></router-link>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'AuthorBooks',
  props: ['author_id'],

  data: () => ({

  }),

  computed: {
    books() {
      return this.$store.getters.getBooks;
    }
  },

  created () {
    this.$store.dispatch('fetchAuthorBook', this.author_id);
  },

  methods: {

  }
};
</script>
