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
              color="primary"
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
                      <v-text-field v-model="editedItem.title" :rules="titleRules" label="Tytuł książki"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.isbn" :rules="isbnRules" label="ISBN"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.description" :rules="descriptionRules" label="Opis"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.publish_year" :rules="publishYearRules" label="Rok wydania"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.amount" :rules="amountRules" label="Ilość"></v-text-field>
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

          <v-dialog v-model="editImageDialog" max-width="700px">
            <v-card>
              <v-card-text>
                <v-container>
                  <v-row align="center" justify="center">
                    <v-col cols="6">
                      <v-card>
                        <v-img
                          v-bind:src="('../storage/' + image)"
                          aspect-ratio="1"
                        ></v-img>
                      </v-card>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions style="justify-content: center; display: block ruby; text-align: center">
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeImage">Anuluj</v-btn>
                <v-btn
                    color="primary"
                    class="text-none pl-5 pr-5"
                    style="margin-right: 15px"
                    rounded
                    depressed
                    :loading="isSelecting"
                    v-model="cover"
                    @click="onButtonClick"
                >
                    <v-icon left>
                        mdi-book
                    </v-icon>
                    {{ buttonText }}
                </v-btn>
                <input
                    ref="uploader"
                    :rules="coverRules"
                    required
                    class="d-none"
                    type="file"
                    accept="image/*"
                    @change="onFileChanged"
                >
                <v-btn
                  color=brown
                  @click="sendImage"
                  :disabled="!valid"
                >
                  Wgraj
                </v-btn>
                </v-card-actions>
            </v-card>
          </v-dialog>

      </v-toolbar>
    </template>

    <template v-slot:[`item.cover`]="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="showImage(item)"
      >
        mdi-image
      </v-icon>
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

    <template v-slot:[`item.amount`]="{ item }">
      <v-chip :color="getColor(item.amount)" dark>{{ item.amount }}</v-chip>
    </template>
  </v-data-table>
</template>

<script>
/*eslint-disable*/
import axios from 'axios';

export default {
  data: () => ({
    editBookDialog: false,
    editImageDialog: false,
    defaultButtonText: 'Zmień zdjęcie okładki',
    isSelecting: false,
    valid: false,
    search: '',
    cover: [],
    image: '',
    show: false,
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Autor', value: 'fullName' },
      { text: 'Kategoria', value: 'categoryName' },
      { text: 'ISBN', value: 'isbn' },
      { text: 'Opis', value: 'description' },
      { text: 'Rok wydania', value: 'publish_year' },
      { text: 'Wydawnictwo', value: 'publisherName' },
      { text: 'Ilość', value: 'amount' },
      { text: 'Okładka', value: 'cover' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      title: '',
      isbn: '',
      description: '',
      publish_year: '',
      amount: ''
    },
    defaultItem: {
      title: '',
      isbn: '',
      description: '',
      publish_year: '',
      amount: ''
    },
    titleRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => v.length <= 60 || 'Tytuł jest za długi!'
    ],
    isbnRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => /^\d+$/.test(v) || 'Numer ISBN musi być prawidłowy',
      (v) => v.length === 13 || 'Numer ISBN powinien zawierać 13 cyfr'
    ],
    amountRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => /^\d+$/.test(v) || 'Ilość musi być prawidłowa',
      (v) => v > 0 || 'Ilość książek powinna być większa od 0'
    ],
    descriptionRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => v.length >= 25 || 'Opis jest za krótki!'
    ],
    publishYearRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => /^\d+$/.test(v) || 'Rok wydania musi być prawidłowy',
      (v) => v.length === 4 || 'Rok wydania powinien zawierać 4 cyfry'
    ],
    coverRules: [
      (v) => !!v || 'Zdjęcie okładki książki jest wymagane!',
      (v) => v.size < 2000000 || 'Zdjęcie okładki powinno być mniejsze niż 2MB!'
    ]
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Edytuj dane o książce' : 'Edytuj dane o książce';
    },
    books() {
      return this.$store.getters.getBooks;
    },
    buttonText() {
      return this.selectedFile ? this.selectedFile.name : this.defaultButtonText;
    }
  },

  watch: {
    dialog (val) {
      val || this.close();
    }
  },

  created () {
    this.$store.dispatch('fetchBooks', {});
  },

  methods: {
    editBook (item) {
      this.editedIndex = this.books.indexOf(item);
      this.editedItem = { ...item };
      this.editBookDialog = true;
    },

    addBook() {
      if (this.editedIndex > -1) {
        Object.assign(this.books[this.editedIndex], this.editedItem);
        axios.put(`/api/book/edit/${this.editedItem.id}`, {
          title: this.editedItem.title,
          isbn: this.editedItem.isbn,
          description: this.editedItem.description,
          publish_year: this.editedItem.publish_year,
          amount: this.editedItem.amount
        });
      }
      this.$store.dispatch('fetchBooks', {});
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
          axios.delete(`/api/book/delete/${item.id}`, {});
          this.books.splice(index, 1);
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
    },

    closeImage () {
      this.editImageDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    showImage (item) {
      this.image = item.cover;
      this.editedIndex = this.books.indexOf(item);
      this.editedItem = { ...item };
      this.editImageDialog = true;
    },

    onButtonClick() {
      this.isSelecting = true;
      window.addEventListener('focus', () => {
        this.isSelecting = false;
      }, { once: true });

      this.$refs.uploader.click();
    },

    onFileChanged(e) {
      this.cover = e.target.files[0];
      this.valid = true;
    },

    sendImage () {
      const formData = new FormData();
      formData.append('cover', this.cover); // formData nie działa z PUT ale z POST

      const config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      };
      axios.post(`/api/book/changeImage/${this.editedItem.id}`, formData, config)
        .then((response) => {
          if (response.data.success === true) {
            this.$swal('Dodano', 'Pomyślnie zmieniono okładkę książki!', 'success');
            this.editImageDialog = false;
          } else {
            this.$swal('Błąd', 'Okładka książki nie mogła zostać zmieniona!', 'error');
          }
        });
      this.$store.dispatch('fetchBooks', {});
    },

    getColor (amount) {
      if (amount === 0) return 'red';
      return 'green';
    }
  }
};
</script>