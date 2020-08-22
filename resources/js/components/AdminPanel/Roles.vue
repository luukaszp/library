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

          <v-dialog v-model="editRolesDialog" max-width="500px">
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.is_worker" label="Pracownik"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.is_admin" label="Admin"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                <v-btn color="blue darken-1" text @click="addRole">Zapisz</v-btn>
                </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
    </template>

    <template v-slot:[`item.actions`]="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editRole(item)"
      >
        mdi-pencil
      </v-icon>
    </template>
    <template v-slot:no-data>
      <p class="pt-5">Brak użytkowników</p>
    </template>
  </v-data-table>
</template>

<script>
  export default {
    data: () => ({
      editRolesDialog: false,
      headers: [
        {
          text: 'ID',
          align: 'start',
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
        this.editRolesDialog = true
      },

      addRole() {
        if (this.editedIndex > -1) {
          Object.assign(this.roles[this.editedIndex], this.editedItem)
          axios.put('/api/roles/edit/'+ this.editedItem.id, {
            is_worker: this.editedItem.is_worker,
            is_admin: this.editedItem.is_admin,
          })
          } 
          this.close()
      },

      close () {
        this.editRolesDialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
    },
  }
</script>