<template>
    <v-container>
        <v-row style="margin-left: 10px; margin-right: 10px">
            <v-col md=3 style="justify-content: center; text-align: center;">
                <v-avatar size="200">
                    <v-img class="card-img" v-bind:src="('../storage/' + authors.photo)"></v-img>
                </v-avatar>
            </v-col>

            <v-col style="text-align: left">
                <h2>{{authors.name + ' ' + authors.surname}}</h2>
                <v-divider></v-divider>
                <p>{{authors.description}}</p>
            </v-col>
        </v-row>

        <v-divider></v-divider>

        <v-row style="margin-left: 10px; margin-right: 10px; justify-content: center;">
            <AuthorBooks v-bind:author_id="author_id"/>
        </v-row>

    </v-container>
</template>

<script>
/*eslint-disable*/
import AuthorBooks from './AuthorBooks.vue';

export default {
  name: 'AuthorView',
  props: ['author_id'],
  components: {
    AuthorBooks
  },

  data: () => ({

  }),

  computed: {
    authors() {
      return this.$store.getters.getAuthors;
    }
  },

  created () {
    this.$store.dispatch('fetchOneAuthor', this.author_id);
  },

  methods: {

  }
};
</script>