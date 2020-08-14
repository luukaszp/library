<template>
  <v-data-table
    :headers="headers"
    :search="search"
    :items="books"
    sort-by="card_number"
    class="elevation-1"
  >
  <template #item.fullName="{ item }"> {{ item.authorName }} {{ item.surname }} </template>
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
                      <v-text-field v-model="editedItem.title" label="Tytuł książki"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.isbn" label="ISBN"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.description" label="Opis"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.publish_year" label="Rok wydania"></v-text-field>
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
import AddBook from "./AddBook.vue";

  export default {
    data: () => ({
      editBookDialog: false,
      search: '',
      headers: [
        {
          text: 'Tytuł książki',
          align: 'start',
          value: 'title',
        },
        { text: 'Autor', value: 'fullName' },
        { text: 'Kategoria', value: 'categoryName' },
        { text: 'ISBN', value: 'isbn' },
        { text: 'Opis', value: 'description' },
        { text: 'Rok wydania', value: 'publish_year' },
        { text: 'Wydawnictwo', value: 'publisherName' },
        { text: 'Akcje', value: 'actions', sortable: false },
      ],
      editedIndex: -1,
      editedItem: {
        title: '',
        isbn: '',
        description: '',
        publish_year: '',
      },
      defaultItem: {
        title: '',
        isbn: '',
        description: '',
        publish_year: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Edytuj dane o książce' : 'Edytuj dane o książce'
      },
      books() {
        return this.$store.getters.getBooks;
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
        this.$store.dispatch("fetchBooks", {});
    },

    methods: {
      editBook (item) {
        this.editedIndex = this.books.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.editBookDialog = true
      },

      addBook() {
        if (this.editedIndex > -1) {
          Object.assign(this.books[this.editedIndex], this.editedItem)
          axios.put('/api/book/edit/'+ this.editedItem.id, {
            title: this.editedItem.title,
            isbn: this.editedItem.isbn,
            description: this.editedItem.description,
            publish_year: this.editedItem.publish_year
          })
          } 
          this.close()
      },

      deleteBook (item) {
        const index = this.books.indexOf(item)
        if (confirm('Czy jesteś pewien, że chcesz usunąć tę książkę?')) {
            axios.delete('/api/book/delete/'+ item.id, {})
        }
        this.books.splice(index, 1)
      },

      close () {
        this.editBookDialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
    },
  }
</script>