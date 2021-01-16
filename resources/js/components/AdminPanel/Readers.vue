<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="readers"
    sort-by="card_number"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Czytelnicy</v-toolbar-title>
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
              color="#008D18"
              dark
              class="mb-2"
              :to="'/register-reader'"
            >Dodaj czytelnika</v-btn>
          </template>
          <v-dialog v-model="editReaderDialog" max-width="500px">
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-form v-model="valid" ref="form">
                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.card_number" :rules="cardNumberRules" label="Numer karty"></v-text-field>
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
                <v-btn color="#008D18" text @click="close">Anuluj</v-btn>
                <v-btn color="#008D18" text @click="addReader" :disabled="!valid">Zapisz</v-btn>
                </v-card-actions>
            </v-card>
          </v-dialog>
      </v-toolbar>

    </template>
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editReader(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteReader(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <p class="pt-5">Brak czytelników</p>
    </template>
  </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  data: () => ({
    editReaderDialog: false,
    valid: false,
    search: '',
    readers: [],
    headers: [
      {
        text: 'Numer karty bibliotecznej',
        align: 'start',
        sortable: false,
        value: 'card_number'
      },
      { text: 'Imię', value: 'name' },
      { text: 'Nazwisko', value: 'surname' },
      { text: 'E-mail', value: 'email' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      card_number: '',
      name: '',
      surname: '',
      email: ''
    },
    defaultItem: {
      card_number: '',
      name: '',
      surname: '',
      email: ''
    },
    nameRules: [
      (v) => !!v || 'Imię jest wymagane!',
      (v) => /^[a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]+$/.test(v) || 'Imię powinno zawierać tylko litery'
    ],
    emailRules: [
      (v) => !!v || 'E-mail jest wymagany',
      (v) => /.+@.+\..+/.test(v) || 'E-mail musi być prawidłowy'
    ],
    surnameRules: [
      (v) => !!v || 'Nazwisko jest wymagane!',
      (v) =>/^[a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]+$/.test(v) || 'Nazwisko powinno zawierać tylko litery'
    ],
    cardNumberRules: [
      (v) => !!v || 'Numer karty bibliotecznej jest wymagany!',
      (v) => /^\d+$/.test(v) || 'Numer karty bibliotecznej musi być prawidłowy',
      (v) => v.length === 10 || 'Numer karty bibliotecznej powinien zawierać 10 cyfr'
    ]
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Edytuj dane czytelnika' : 'Edytuj dane czytelnika';
    }
  },

  watch: {
    dialog (val) {
      val || this.close();
    }
  },

  methods: {
    setData(readers) {
      this.readers = readers;
    },

    editReader (item) {
      this.editedIndex = this.readers.indexOf(item);
      this.editedItem = { ...item };
      this.editReaderDialog = true;
    },

    addReader() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.readers[this.editedIndex], this.editedItem);
          axios.put(`/api/reader/edit/${this.editedItem.id}`, {
            card_number: this.editedItem.card_number,
            name: this.editedItem.name,
            surname: this.editedItem.surname,
            email: this.editedItem.email
          });
        }
        axios.get('/api/user/getReaders')
        .then(response => {
            this.readers = response.data
        });
        this.close();
      }
    },

    deleteReader (item) {
      const index = this.readers.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tego czytelnika?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj',
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/reader/delete/${item.id}`, {});
          this.readers.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto czytelnika', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    close () {
      this.editReaderDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
     .get('/api/user/getReaders')
     .then(response => {
       next(vm => vm.setData(response.data));
   });
  }
};
</script>