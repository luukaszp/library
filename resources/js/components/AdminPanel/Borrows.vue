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
          <template>
            <v-btn
              color="primary"
              dark
              class="mb-2"
              :to="'/add-borrow'"
            >Dodaj wypożyczenie</v-btn>
          </template>

          <v-dialog v-model="editBorrowDialog" max-width="500px">
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-form v-model="valid" ref="form">
                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-menu
                        v-model="menu"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                v-model="editedItem.borrows_date"
                                label="Wybierz datę wypożyczenia"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                :rules="borrowsRules"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="editedItem.borrows_date" @input="menu = false"></v-date-picker>
                            </v-menu>
                      </v-col>
                    </v-row>
                  </v-form>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                <v-btn color="blue darken-1" text @click="addBorrow" :disabled="!valid">Zapisz</v-btn>
                </v-card-actions>
            </v-card>
          </v-dialog>
      </v-toolbar>
    </template>

    <template v-slot:[`item.actions`]="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editBorrow(item)"
      >
        mdi-pencil
      </v-icon>
    </template>
    <template v-slot:no-data>
      <p class="pt-5">Brak wypożyczeń</p>
    </template>

    <template v-slot:[`item.is_available`]="{ item }">
      <v-simple-checkbox v-model="item.is_available" @click="returnBook(item)"></v-simple-checkbox>
    </template>

    <template v-slot:[`item.returns_date`]="{ item }">
      <v-chip :color="getColor(item.returns_date)" dark>{{ item.returns_date }}</v-chip>
    </template>
  </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  data: () => ({
    editBorrowDialog: false,
    valid: false,
    search: '',
    show: false,
    menu: false,
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Czytelnik', value: 'fullName' },
      { text: 'Wypożyczenie', value: 'borrows_date' },
      { text: 'Termin zwrotu', value: 'returns_date' },
      { text: 'Potwierdź oddanie', value: 'is_available' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      title: '',
      fullName: '',
      borrows_date: '',
      returns_date: ''
    },
    defaultItem: {
      title: '',
      fullName: '',
      borrows_date: '',
      returns_date: ''
    },
    borrowsRules: [
      (v) => !!v || 'Data wypożyczenia jest wymagana'
    ]
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Edytuj dane o wypożyczeniu' : 'Edytuj dane o wypożyczeniu';
    },
    borrows() {
      return this.$store.getters.getBorrows;
    }
  },

  watch: {
    dialog (val) {
      val || this.close();
    }
  },

  created () {
    this.$store.dispatch('fetchBorrows', {});
  },

  methods: {
    editBorrow (item) {
      this.editedIndex = this.borrows.indexOf(item);
      this.editedItem = { ...item };
      this.editBorrowDialog = true;
    },

    addBorrow() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.borrows[this.editedIndex], this.editedItem);
          axios.put(`/api/borrow/edit/${this.editedItem.id}`, {
            borrows_date: this.editedItem.borrows_date
          });
        }
        this.close();
        this.$store.dispatch('fetchBorrows', {});
      }
    },

    close () {
      this.editBorrowDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    getColor (returns_date) {
      const todayDate = new Date();
      const dateOfReturn = new Date(returns_date);
      const differenceInTime = dateOfReturn.getTime() - todayDate.getTime();
      const differenceInDays = Math.round(differenceInTime / (1000 * 3600 * 24));

      if (differenceInDays < 3) return 'red';
      if (differenceInDays < 10) return 'orange';
      return 'green';
    },

    returnBook(item) {
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz potwierdzić oddanie książki?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Zwróć',
        cancelButtonText: 'Anuluj'
      }).then((result) => {
        if (result.value) {
          axios.put(`/api/borrow/returnBook/${item.id}`, {
            is_available: item.is_available,
            bookID: item.bookID
          });
          this.$swal('Zatwierdzono', 'Pomyślnie zwrócono książkę do biblioteki', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
        this.$store.dispatch('fetchBorrows', {});
      });
    }
  }
};
</script>