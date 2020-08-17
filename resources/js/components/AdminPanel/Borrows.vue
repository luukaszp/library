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
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.title" label="Tytuł książki"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.fullName" label="Czytelnik"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.borrows_date" label="Data wypożyczenia"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.returns_date" label="Data zwrotu"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                <v-btn color="blue darken-1" text @click="addBorrow">Zapisz</v-btn>
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
  </v-data-table>
</template>

<script>
import AddBorrow from "./AddBorrow.vue";

  export default {
    data: () => ({
      editBorrowDialog: false,
      search: '',
      show: false,
      headers: [
        {
          text: 'Tytuł książki',
          align: 'start',
          value: 'title',
        },
        { text: 'Czytelnik', value: 'fullName' },
        { text: 'Wypożyczenie', value: 'borrows_date' },
        { text: 'Zwrot', value: 'returns_date' },
        { text: 'Akcje', value: 'actions', sortable: false },
      ],
      editedIndex: -1,
      editedItem: {
        title: '',
        fullName: '',
        borrows_date: '',
        returns_date: '',
      },
      defaultItem: {
        title: '',
        fullName: '',
        borrows_date: '',
        returns_date: '',
      }
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Edytuj dane o wypożyczeniu' : 'Edytuj dane o wypożyczeniu'
      },
      borrows() {
        return this.$store.getters.getBorrows;
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
        this.$store.dispatch("fetchBorrows", {});
    },

    methods: {
      editBorrow (item) {
        this.editedIndex = this.borrows.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.editBorrowDialog = true
      },

      addBorrow() {
        if (this.editedIndex > -1) {
          Object.assign(this.borrows[this.editedIndex], this.editedItem)
          axios.put('/api/borrow/edit/'+ this.editedItem.id, {
            title: this.editedItem.title,
            fullName: this.editedItem.fullName,
          })
          } 
          this.close()
      },

      close () {
        this.editBookDialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      }
    },
  }
</script>