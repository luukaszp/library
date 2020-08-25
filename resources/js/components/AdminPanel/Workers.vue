<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="workers"
    sort-by="id_number"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Pracownicy</v-toolbar-title>
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
          <template>
            <v-btn
              color="primary"
              dark
              class="mb-2"
              :to="'/register-worker'"
            >Dodaj pracownika</v-btn>
          </template>
          <v-dialog v-model="editWorkerDialog" max-width="500px">
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-form v-model="valid" ref="form">
                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.id_number" :rules="idNumberRules" label="Numer identyfikacyjny"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.name" :rules="nameRules" label="Imię"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.surname" :rules="surnameRules" label="Nazwisko"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.email" :rules="emailRules" label="E-mail"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-form>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                <v-btn color="blue darken-1" text @click="addWorker" :disabled="!valid">Zapisz</v-btn>
                </v-card-actions>
            </v-card>
          </v-dialog>
      </v-toolbar>

    </template>
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editWorker(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteWorker(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <p class="pt-5">Brak pracowników</p>
    </template>
  </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  data: () => ({
    editWorkerDialog: false,
    valid: false,
    search: '',
    headers: [
      {
        text: 'Numer identyfikacyjny',
        align: 'start',
        sortable: false,
        value: 'id_number'
      },
      { text: 'Imię', value: 'name' },
      { text: 'Nazwisko', value: 'surname' },
      { text: 'E-mail', value: 'email' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      id_number: '',
      name: '',
      surname: '',
      email: ''
    },
    defaultItem: {
      id_number: '',
      name: '',
      surname: '',
      email: ''
    },
    emailRules: [
      (v) => !!v || 'E-mail jest wymagany',
      (v) => /.+@.+\..+/.test(v) || 'E-mail musi być prawidłowy'
    ],
    nameRules: [
      (v) => !!v || 'Imię jest wymagane!',
      (v) => /^[a-zA-Z]+$/.test(v) || 'Imię powinno zawierać tylko litery'
    ],
    surnameRules: [
      (v) => !!v || 'Nazwisko jest wymagane!',
      (v) => /^[a-zA-Z]+$/.test(v) || 'Nazwisko powinno zawierać tylko litery'
    ],
    idNumberRules: [(v) => !!v || 'Identyfikator jest wymagany!',
      (v) => /^\d+$/.test(v) || 'Identyfikator musi być prawidłowy',
      (v) => v.length === 12 || 'Identyfikator powinien zawierać 12 cyfr'
    ]
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Edytuj dane pracownika' : 'Edytuj dane pracownika';
    },
    workers() {
      return this.$store.getters.getWorkers;
    }
  },

  watch: {
    dialog (val) {
      val || this.close();
    }
  },

  created () {
    this.$store.dispatch('fetchWorkers', {});
  },

  methods: {
    editWorker (item) {
      this.editedIndex = this.workers.indexOf(item);
      this.editedItem = { ...item };
      this.editWorkerDialog = true;
    },

    addWorker() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.workers[this.editedIndex], this.editedItem);
          axios.put(`/api/worker/edit/${this.editedItem.id}`, {
            id_number: this.editedItem.id_number,
            name: this.editedItem.name,
            surname: this.editedItem.surname,
            email: this.editedItem.email
          });
        }
        this.close();
      }
    },

    deleteWorker (item) {
      const index = this.workers.indexOf(item);
      if (confirm('Czy jesteś pewien, że chcesz usunąć tego pracownika?')) {
        axios.delete(`/api/worker/delete/${item.id}`, {});
      }
      this.workers.splice(index, 1);
    },

    close () {
      this.editWorkerDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    }
  }
};
</script>