<template>
  <v-data-table
    :headers="headers"
    :items="roles"
    sort-by="id"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Role użytkowników</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>       
      </v-toolbar>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editRole(item)"
      >
        mdi-pencil
      </v-icon>
    </template>
    <template v-slot:no-data>
      <h1>Brak użytkowników</h1>
    </template>
  </v-data-table>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      headers: [
        {
          text: 'ID',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        { text: 'Imię', value: 'name' },
        { text: 'Nazwisko', value: 'surname' },
        { text: 'E-mail', value: 'email' },
        { text: 'Pracownik', value: 'is_worker' },
        { text: 'Admin', value: 'is_admin' },
        { text: 'Akcje', value: 'actions', sortable: false },
      ],
      editedIndex: -1,
      editedItem: {
        id: '',
        name: '',
        surname: '',
        email: '',
        is_worker: '',
        is_admin: '',
      },
      defaultItem: {
        id_number: '',
        name: '',
        surname: '',
        email: '',
        is_worker: '',
        is_admin: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Nowa rola' : 'Edytuj rolę'
      },
      roles() {
        return this.$store.getters.getRoles;
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
        this.$store.dispatch("fetchRoles", {});
    },

    methods: {
      editRole (item) {
        this.editedIndex = this.roles.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
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
          Object.assign(this.roles[this.editedIndex], this.editedItem)
        } else {
          this.roles.push(this.editedItem)
        }
        this.close()
      },
    },
  }
</script>