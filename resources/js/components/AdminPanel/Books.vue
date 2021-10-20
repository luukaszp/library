<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="books"
    :expanded.sync="expanded"
    show-expand
    :single-expand="true"
    sort-by="card_number"
    class="elevation-1"
    style="word-break: initial"
  >
  <template #[`item.fullName`]="{ item }"> {{ item.authorName }} {{ item.surname }} </template>

  <template v-slot:expanded-item="{ headers, item }">
    <td :colspan="headers.length">
        Opis książki: {{ item.description }}
    </td>
  </template>

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
              <v-card-title style="justify-content: center">
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="5">
                      <v-text-field v-model="editedItem.title" :rules="titleRules" label="Tytuł książki"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="5">
                      <v-text-field v-model="editedItem.publish_year" :rules="publishYearRules" label="Rok wydania"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="12">
                      <v-text-field v-model="editedItem.description" :rules="descriptionRules" label="Opis"></v-text-field>
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

          <v-dialog v-model="editImageDialog" max-width="550px" class="rounded-xl">
            <v-card>
              <v-card-text>
                <v-container>
                  <v-row align="center" justify="center">
                    <v-col cols="8">
                      <v-card>
                        <v-img
                          v-bind:src="('https://library-site.s3.eu-north-1.amazonaws.com/covers/' + image)"
                          aspect-ratio="1"
                          style="height: 400px;width: 320px;"
                        ></v-img>
                      </v-card>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions style="justify-content: center; display: flex; text-align: center; padding-bottom: 25px">
                <v-spacer></v-spacer>
                <v-btn color="#008D18" text @click="closeImage">Anuluj</v-btn>
                <v-file-input
                    v-model="cover"
                    accept="image/png, image/jpeg, image/bmp"
                    label="Wybierz zdjęcie"
                    :rules="coverRules"
                    outlined
                    dense
                    prepend-icon=""
                    prepend-inner-icon="mdi-book"
                    hide-details
                    @change="onFileChanged"
                    >
                </v-file-input>
                <v-btn
                color=#228B22
                @click="sendImage"
                :disabled="!valid"
                text
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
    defaultButtonText: 'Zmień zdjęcie okładki (*.jpg)',
    isSelecting: false,
    valid: false,
    search: '',
    cover: [],
    books: [],
    image: '',
    show: false,
    expanded: [],
    headers: [
      {
        text: 'Tytuł książki',
        align: 'start',
        value: 'title'
      },
      { text: 'Autor', value: 'fullName' },
      { text: 'Kategoria', value: 'categoryName' },
      { text: 'Rok wydania', value: 'publish_year' },
      { text: 'Wydawnictwo', value: 'publisherName' },
      { text: 'Ilość', value: 'amount' },
      { text: 'Okładka', value: 'cover' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      title: '',
      description: '',
      publish_year: '',
    },
    defaultItem: {
      title: '',
      description: '',
      publish_year: '',
    },
    titleRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => v.length <= 60 || 'Tytuł jest za długi!'
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
    buttonText() {
      return this.selectedFile ? this.selectedFile.name : this.defaultButtonText;
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
        axios.put(`/api/book/edit/${this.editedItem.id}`, {
          title: this.editedItem.title,
          description: this.editedItem.description,
          publish_year: this.editedItem.publish_year,
        });
      }
      this.$store.dispatch('fetchBooks', {});
      this.close();
    },

    deleteBook (item) {
      const index = this.books.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć te książki?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj'
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/book/delete/group/${item.id}`, {});
          this.books.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto książki', 'success');
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

    onFileChanged() {
      this.valid = true;
    },

    sendImage () {
      const blob = this.cover
      const formData = new FormData();
      formData.append('cover', blob);

      };
      axios.post(`/api/book/changeImage/${this.editedItem.id}`, formData)
        .then((response) => {
          if (response.data.success === true) {
            this.$swal('Dodano', 'Pomyślnie zmieniono okładkę książki!', 'success');
            this.editImageDialog = false;
          } else {
            this.$swal('Błąd', 'Okładka książki nie mogła zostać zmieniona!', 'error');
          }
        });
      axios.get('/api/book/getBooks')
        .then(response => {
            this.books = response.data
        });
    },

    getColor (amount) {
      if (amount === '0') return 'red';
      if (amount === '1' || amount === '2') return 'orange';
      return 'green';
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
     .get('/api/book/getBooks')
     .then(response => {
       next(vm => vm.setData(response.data));
   });
  }
};
</script>
