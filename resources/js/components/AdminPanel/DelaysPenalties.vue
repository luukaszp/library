<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="borrows"
    sort-by="title"
    class="elevation-1"
  >
  <template #[`item.fullName`]="{ item }"> {{ item.name }} {{ item.surname }} </template>

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
/* eslint-disable */
export default {
  data: () => ({
    search: '',
    show: false,
    borrows: [],
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Czytelnik', value: 'fullName' },
      { text: 'Opóźnienie (dni)', value: 'delay' },
      { text: 'Kara pieniężna (zł)', value: 'penalty' }
    ]
  }),

  methods: {
    setData(borrows) {
      this.borrows = borrows;
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
     .get('/api/borrow/getDelayed')
     .then(response => {
       next(vm => vm.setData(response.data));
   });
  }
};
</script>