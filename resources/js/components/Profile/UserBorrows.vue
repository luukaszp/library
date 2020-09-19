<template>
    <v-data-table
    :headers="headers"
    :search="search"
    :items="borrows"
    sort-by="title"
    class="elevation-1"
    style="width: 100%"
  >
  <template #[`item.fullName`]="{ item }"> {{ item.name }} {{ item.surname }} </template>

    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Wypożyczenia</v-toolbar-title>
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
      <p class="pt-5">Brak wypożyczeń</p>
    </template>
  </v-data-table>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'Profile',
  props: ['user_id'],

  data: () => ({
    search: '',
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Czytelnik', value: 'fullName' },
      { text: 'Wypożyczenie', value: 'borrows_date' },
      { text: 'Termin zwrotu', value: 'returns_date' },
      { text: 'Data oddania', value: 'when_returned' },
    ]
  }),

  computed: {
    readers() {
      return this.$store.getters.getReaders;
    },
    borrows() {
      return this.$store.getters.getBorrows;
    }
  },

  created () {
    this.$store.dispatch('fetchOneReader', this.user_id);
    this.$store.dispatch('fetchSpecificBorrow', this.user_id);
  },

  methods: {

  }
};
</script>