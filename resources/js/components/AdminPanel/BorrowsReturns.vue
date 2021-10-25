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
        <v-toolbar-title style="width: 100%">Historia wypożyczeń</v-toolbar-title>
        <v-divider
          class="mx-4 d-none d-sm-flex"
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

    <template v-slot:[`item.returns_date`]="{ item }">
      <v-chip :color="getColor(item.returns_date)" dark>{{ item.returns_date }}</v-chip>
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
      { text: 'Wypożyczenie', value: 'borrows_date' },
      { text: 'Termin zwrotu', value: 'returns_date' },
      { text: 'Opóźnienie', value: 'delay' },
      { text: 'Kara', value: 'penalty' },
      { text: 'Kiedy oddano', value: 'when_returned' }
    ]
  }),

  methods: {
    setData(borrows) {
      this.borrows = borrows;
    },

    getColor (returns_date) {
      const todayDate = new Date();
      const dateOfReturn = new Date(returns_date);
      const differenceInTime = dateOfReturn.getTime() - todayDate.getTime();
      const differenceInDays = Math.round(differenceInTime / (1000 * 3600 * 24));

      if (differenceInDays < 0) return 'red';
      if (differenceInDays < 7) return 'orange';
      return 'green';
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
     .get('/api/borrow/history')
     .then(response => {
       next(vm => vm.setData(response.data));
   });
  }
};
</script>
