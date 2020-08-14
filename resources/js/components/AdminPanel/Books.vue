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
      dialog: false,
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
        authorName: '',
        categoryName: '',
        isbn: '',
        description: '',
        publish_year: '',
        publisherName: '',
      },
      defaultItem: {
        title: '',
        authorName: '',
        categoryName: '',
        isbn: '',
        description: '',
        publish_year: '',
        publisherName: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Nowa książka' : 'Edytuj dane o książce'
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
        this.dialog = true
      },

      deleteBook (item) {
        const index = this.books.indexOf(item)
        if (confirm('Czy jesteś pewien, że chcesz usunąć tę książkę?')) {
            axios.delete('/api/book/delete', {
                data: {bookId: item.id}
            })
        }
        this.books.splice(index, 1)
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.readers[this.editedIndex], this.editedItem)
        } else {
          this.readers.push(this.editedItem)
        }
        this.close()
      },
    },
  }
</script>