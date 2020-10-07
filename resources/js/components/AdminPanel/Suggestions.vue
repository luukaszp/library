<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="suggestions"
    sort-by="card_number"
    class="elevation-1"
    style="word-break: initial"
  >

  <template #[`item.fullName`]="{ item }"> {{ item.name }} {{ item.surname }} </template>

    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Sugestie</v-toolbar-title>
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
      <v-icon
        small
        @click="deleteSugestion(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <p class="pt-5">Brak sugestii</p>
    </template>
  </v-data-table>
</template>

<script>
/*eslint-disable*/
import axios from 'axios';

export default {
  data: () => ({
    search: '',
    headers: [
      {
        text: 'Rodzaj sugestii',
        align: 'start',
        value: 'type'
      },
      { text: 'Opis', value: 'description' },
      { text: 'Czytelnik', value: 'fullName' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ]
  }),

  computed: {
    suggestions() {
      return this.$store.getters.getSuggestions;
    }
  },

  created () {
    this.$store.dispatch('fetchSuggestions', {});
  },

  methods: {
    deleteSugestion (item) {
      const index = this.suggestions.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tę sugestię?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj'
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/suggestions/delete/${item.id}`, {});
          this.suggestions.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto sugestię użytkownika', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    }
  }
};
</script>