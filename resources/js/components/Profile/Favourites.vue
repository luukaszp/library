<template>
    <v-row style="justify-content: center; text-align: center">
        <v-col md=5>
            <h1>Ulubieni autorzy</h1>
        </v-col>

        <v-divider vertical></v-divider>

        <v-col md=5>
            <h1>Ulubione książki</h1>
        </v-col>
    </v-row>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'Favourites',
  props: ['user_id'],

  data: () => ({
    favourites: [],
  }),

  computed: {
    /*readers() {
      return this.$store.getters.getReaders;
    },
    borrows() {
      return this.$store.getters.getBorrows;
    }*/
  },

  async created () {
    /*this.$store.dispatch('fetchOneReader', this.user_id);
    this.$store.dispatch('fetchSpecificDelay', this.user_id);*/
  },

  methods: {

  }
};
</script>