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

    <template v-slot:[`item.actions`]="{ item }">
      <v-btn
        class="mr-2"
        color="success"
        @click="extendDate(item)"
      >
        Przedłuż termin
      </v-btn>
    </template>

    <template v-slot:no-data>
      <p class="pt-5">Brak wypożyczeń</p>
    </template>
  </v-data-table>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'UserBorrows',
  props: ['user_id'],

  data: () => ({
    search: '',
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Autor', value: 'fullName' },
      { text: 'Wypożyczenie', value: 'borrows_date' },
      { text: 'Termin zwrotu', value: 'returns_date' },
      { text: 'Akcje', value: 'actions', sortable: false }
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
    extendDate(item) {
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz przedłużyć termin tej książki?',
        text: "Przedłużyć można jedną książkę o 7 dni.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Przedłuż',
        confirmButtonColor: '#5cb85c',
        cancelButtonColor: '#d9534f',
        cancelButtonText: 'Anuluj',
      }).then((result) => {
        if (result.value) {
          axios.put(`/api/borrow/extend/${item.id}`, {});
          this.$swal('Przedłużono', 'Termin oddania książki został pomyślnie przedłużony!', 'success');
          this.$store.dispatch('fetchSpecificBorrow', this.user_id);
        }
      });
    }
  }
};
</script>