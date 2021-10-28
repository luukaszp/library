<template>
  <v-data-table
    :headers="headers"
    :items="roles"
    :search="search"
    sort-by="id"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title style="width: 100%">Role użytkowników</v-toolbar-title>
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

          <v-dialog v-model="editRolesDialog" max-width="250px" persistent>
            <v-card>
              <v-card-title style="justify-content: center">
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-form v-model="valid" ref="form">
                    <v-row>
                      <v-col cols="12" sm="12" md="12">
                        <v-text-field v-model="editedItem.is_admin" :rules="roleRules" label="Admin"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-form>
                </v-container>
              </v-card-text>

              <v-card-actions style="text-align: center; display: block">
                <v-spacer></v-spacer>
                <v-btn color="#008D18" text @click="close">Anuluj</v-btn>
                <v-btn color="#008D18" text @click="addRole" :disabled="!valid">Zapisz</v-btn>
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
/* eslint-disable */
import axios from 'axios';

export default {
  data: () => ({
    editRolesDialog: false,
    valid: false,
    roles: [],
    search: '',
    headers: [
      {
        text: 'ID',
        align: 'start',
        value: 'id'
      },
      { text: 'Imię', value: 'name' },
      { text: 'Nazwisko', value: 'surname' },
      { text: 'E-mail', value: 'email' },
      { text: 'Admin', value: 'is_admin' },
      { text: 'Akcje', value: 'actions', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      id: '',
      name: '',
      surname: '',
      email: '',
      is_admin: ''
    },
    defaultItem: {
      id_number: '',
      name: '',
      surname: '',
      email: '',
      is_admin: ''
    },
    roleRules: [
      (v) => !!v || 'Pole jest wymagane!',
      (v) => /^\d+$/.test(v) || 'Rola musi zostać poprawnie wprowadzona',
      (v) => v >= 0 && v <= 1 || 'Pole typu boolean. Proszę podać 0 lub 1'
    ]
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Nowa rola' : 'Edytuj rolę';
    }
  },

  methods: {
    setData(roles) {
      this.roles = roles;
    },

    editRole (item) {
      this.editedIndex = this.roles.indexOf(item);
      this.editedItem = { ...item };
      this.editRolesDialog = true;
    },

    addRole() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.roles[this.editedIndex], this.editedItem);
          axios.put(`/api/roles/edit/${this.editedItem.id}`, {
            is_worker: this.editedItem.is_worker,
            is_admin: this.editedItem.is_admin
          });
        }
        this.$store.dispatch('fetchRoles', {})
        this.close();
      }
    },

    close () {
      this.editRolesDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    }
  },

  beforeRouteEnter (to, from, next) {
    axios
     .get('/api/user/getRoles')
     .then(response => {
       next(vm => vm.setData(response.data));
   });
  }
};
</script>

