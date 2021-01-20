<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="books"
    sort-by="card_number"
    class="elevation-1"
    style="word-break: initial"
  >
  <template #[`item.fullName`]="{ item }"> {{ item.authorName }} {{ item.surname }} </template>

    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Książki</v-toolbar-title>
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
              :to="'/add-book'"
            >Dodaj książkę</v-btn>
          </template>

          <v-dialog v-model="editBookDialog" max-width="500px">
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.isbn" :rules="isbnRules" label="ISBN"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                <v-btn color="blue darken-1" text @click="addBook">Zapisz</v-btn>
                </v-card-actions>
            </v-card>
          </v-dialog>
      </v-toolbar>
    </template>

    <template v-slot:[`item.actions`]="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editBook(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteBook(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <p class="pt-5">Brak książek</p>
    </template>
  </v-data-table>
</template>

<script>
/*eslint-disable*/
import axios from 'axios';

export default {
  data: () => ({
    editBookDialog: false,
    valid: false,
    search: '',
    books: [],
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Autor', value: 'fullName' },
      { text: 'ISBN', value: 'isbn' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      isbn: '',
    },
    defaultItem: {
      isbn: '',
    },
    isbnRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => /^\d+$/.test(v) || 'Numer ISBN musi być prawidłowy',
      (v) => v.length === 13 || 'Numer ISBN powinien zawierać 13 cyfr'
    ]
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Edytuj dane o książce' : 'Edytuj dane o książce';
    }
  },

  watch: {
    dialog (val) {
      val || this.close();
    }
  },

  methods: {
    setData(books) {
      this.books = books;
    }, 

    editBook (item) {
      this.editedIndex = this.books.indexOf(item);
      this.editedItem = { ...item };
      this.editBookDialog = true;
    },

    addBook() {
      if (this.editedIndex > -1) {
        Object.assign(this.books[this.editedIndex], this.editedItem);
        axios.put(`/api/book/edit/isbn/${this.editedItem.id}`, {
          isbn: this.editedItem.isbn,
        });
      }
      this.$store.dispatch('fetchBooksISBN', {});
      this.close();
    },

    deleteBook (item) {
      const index = this.books.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tę książkę?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj'
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/book/delete/${item.id}`, {})
          this.books.splice(index, 1);
          this.$store.dispatch('fetchBooksISBN', {});
          this.$swal('Usunięto', 'Pomyślnie usunięto książkę', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    close () {
      this.editBookDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
     .get('/api/book/getBooks/isbn')
     .then(response => {
       next(vm => vm.setData(response.data));
   });
  }
};
</script>