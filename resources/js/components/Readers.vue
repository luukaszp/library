<template>
  <v-data-table
    :headers="headers"
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
          <template>
            <v-btn
              color="primary"
              dark
              class="mb-2"
              :to="'/register-reader'"
            >Dodaj czytelnika</v-btn>
          </template>
          
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
import RegisterReader from "../components/RegisterReader.vue";

  export default {
    data: () => ({
      dialog: false,
      headers: [
        {
          text: 'Numer karty bibliotecznej',
          align: 'start',
          sortable: false,
          value: 'card_number',
        },
        { text: 'Imię', value: 'name' },
        { text: 'Nazwisko', value: 'surname' },
        { text: 'E-mail', value: 'email' },
        { text: 'Akcje', value: 'actions', sortable: false },
      ],
      editedIndex: -1,
      editedItem: {
        card_number: '',
        name: '',
        surname: '',
        email: '',
      },
      defaultItem: {
        card_number: '',
        name: '',
        surname: '',
        email: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Nowy czytelnik' : 'Edytuj dane czytelnika'
      },
      readers() {
        return this.$store.getters.getReaders;
      }
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