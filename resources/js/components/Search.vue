<template>
            <v-data-table
                    :headers="headers"
                    :items="items"
                    :search="search"
                    sort-by="amount"
                    class="elevation-10"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Wyszukiwarka</v-toolbar-title>
                        <v-divider
                                class="mx-4"
                                inset
                                vertical
                        ></v-divider>
                        <v-spacer></v-spacer>
                        <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                                class="mr-3"
                        ></v-text-field>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak wyników</p>
                    </template>

            </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  name: 'Publishers',
  data: () => ({
    valid: false,
    search: '',
    headers: [
      {
        text: 'Wyniki wyszukiwania',
        align: 'start',
        value: 'name'
      },
      //{ text: 'Akcje', value: 'action', sortable: false }
    ],
  }),

  computed: {
    items() {
      return this.$store.getters.getEvents;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.$store.dispatch('fetchEvents', {});
  },
};
</script>

<style scoped>

</style>