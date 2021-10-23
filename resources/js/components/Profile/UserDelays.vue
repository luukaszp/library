<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="delays"
    sort-by="title"
    class="elevation-1"
    style="width: 100%"
  >

    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Opóźnienia i kary</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Szukaj"
          single-line
          hide-details
          class="mr-3"
        ></v-text-field>
        <v-spacer></v-spacer>
      </v-toolbar>
    </template>

    <template v-slot:no-data>
      <p class="pt-5">Brak opóźnień</p>
    </template>

  </v-data-table>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'UserDelays',
  props: ['user_id'],

  data: () => ({
    search: '',
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Opóźnienie (dni)', value: 'delay' },
      { text: 'Kara pieniężna (zł)', value: 'penalty' }
    ]
  }),

  computed: {
    readers() {
      return this.$store.getters.getReaders;
    },
    delays() {
      return this.$store.getters.getBorrows;
    }
  },

  created () {
    this.$store.dispatch('fetchOneReader', this.user_id);
    this.$store.dispatch('fetchSpecificDelay', this.user_id);
  },

  methods: {

  }
};
</script>

<style>
@media only screen and (max-width: 600px) {
    .v-data-footer__pagination {
        margin: 0px !important
    }
}
</style>
