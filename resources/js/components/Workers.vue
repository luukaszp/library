<template>
  <v-data-table
    :headers="headers"
    :items="workers"
    sort-by="id_number"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Pracownicy</v-toolbar-title>
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
              :to="'/register-worker'"
            >Dodaj pracownika</v-btn>
          </template>
          
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editWorker(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteWorker(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <h1>Brak pracowników</h1>
    </template>
  </v-data-table>
</template>

<script>
import RegisterWorker from "../components/RegisterWorker.vue";

  export default {
    data: () => ({
      dialog: false,
      headers: [
        {
          text: 'Numer identyfikacyjny',
          align: 'start',
          sortable: false,
          value: 'id_number',
        },
        { text: 'Imię', value: 'name' },
        { text: 'Nazwisko', value: 'surname' },
        { text: 'E-mail', value: 'email' },
        { text: 'Akcje', value: 'actions', sortable: false },
      ],
      editedIndex: -1,
      editedItem: {
        id_number: '',
        name: '',
        surname: '',
        email: '',
      },
      defaultItem: {
        id_number: '',
        name: '',
        surname: '',
        email: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Nowy pracownik' : 'Edytuj dane pracownika'
      },
      workers() {
        return this.$store.getters.getWorkers;
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
        this.$store.dispatch("fetchWorkers", {});
    },

    methods: {
      editWorker (item) {
        this.editedIndex = this.workers.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteWorker (item) {
        const index = this.workers.indexOf(item)
        if (confirm('Czy jesteś pewien, że chcesz usunąć tego pracownika?')) {
            axios.delete('/api/Worker/Delete', {
                data: {workerId: item.id}//dodać to do storej - modules
            })
        }
        this.workers.splice(index, 1)
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
          Object.assign(this.workers[this.editedIndex], this.editedItem)
        } else {
          this.workers.push(this.editedItem)
        }
        this.close()
      },
    },
  }
</script>