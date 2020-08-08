<template>
  <v-data-table
    :headers="headers"
    :items="readers"
    sort-by="cardNumber"
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
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >Dodaj czytelnika</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.cardNumber" label="Numer karty bibliotecznej"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.name" label="Imię"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.surname" label="Nazwisko"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.email" label="E-mail"></v-text-field>
                    </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
              <v-btn color="blue darken-1" text @click="save">Zapisz</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
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
      <h1>Brak czytelników</h1>
    </template>
  </v-data-table>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      headers: [
        {
          text: 'Numer karty bibliotecznej',
          align: 'start',
          sortable: false,
          value: 'cardNumber',
        },
        { text: 'Imię', value: 'name' },
        { text: 'Nazwisko', value: 'surname' },
        { text: 'E-mail', value: 'email' },
        { text: 'Akcje', value: 'actions', sortable: false },
      ],
      readers: [],
      editedIndex: -1,
      editedItem: {
        cardNumber: '',
        name: '',
        surname: '',
        email: '',
      },
      defaultItem: {
        cardNumber: '',
        name: '',
        surname: '',
        email: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Nowy czytelnik' : 'Edytuj dane czytelnika'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
        this.$store.dispatch("fetchReaders", {});
    },

    methods: {
      editReader (item) {
        this.editedIndex = this.readers.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteReader (item) {
        const index = this.readers.indexOf(item)
        if (confirm('Czy jesteś pewien, że chcesz usunąć tego czytelnika?')) {
            axios.delete('/api/Reader/Delete', {
                data: {readerId: item.id}//dodać to do storej - modules
            })
        }
        this.readers.splice(index, 1)
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