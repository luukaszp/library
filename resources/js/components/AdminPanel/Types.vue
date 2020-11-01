<template>
            <v-data-table
                    :headers="headers"
                    :items="types"
                    :search="search"
                    sort-by="amount"
                    class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Typy wydarzeń</v-toolbar-title>
                        <v-divider
                                class="mx-4"
                                inset
                                vertical
                        ></v-divider>
                        <v-spacer></v-spacer>
                        <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                                class="mr-3"
                        ></v-text-field>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="addTypeDialog" max-width="300px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="#3eb4a7" dark class="mb-2" v-on="on">Nowy typ</v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-form v-model="valid" ref="form">
                                            <v-row>
                                                <v-col>
                                                    <v-text-field v-model="editedItem.name" :rules="typeRules" label="Typ"></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-form>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                                    <v-btn color="blue darken-1" text @click="addType" :disabled="!valid">Zapisz</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.action`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editType(item)"
                        >
                        mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteType(item)"
                        >
                        mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak typów</p>
                    </template>

            </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  name: 'Types',
  data: () => ({
    addTypeDialog: false,
    valid: false,
    search: '',
    typeName: '',
    headers: [
      {
        text: 'Typy',
        align: 'start',
        value: 'name'
      },
      { text: 'Akcje', value: 'action', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      name: ''
    },
    defaultItem: {
      name: ''
    },
    typeRules: [
      (v) => !!v || 'Nazwa jest wymagana!',
      (v) => /^[a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]+$/.test(v) || 'Nazwa typu powinna zawierać tylko litery'
    ]
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nowy typ' : 'Edytuj typ';
    },
    types() {
      return this.$store.getters.getTypes;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.$store.dispatch('fetchTypes', {});
  },

  methods: {
    editType(item) {
      this.editedIndex = this.types.indexOf(item);
      this.editedItem = { ...item };
      this.addTypeDialog = true;
    },

    deleteType(item) {
      const index = this.types.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć ten typ wydarzenia?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj',
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/calendar/type/delete/${item.id}`, {});
          this.types.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto typ', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    addType() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.types[this.editedIndex], this.editedItem);
          axios.put(`/api/calendar/type/edit/${this.editedItem.id}`, {
            name: this.editedItem.name
          });
        } else {
          this.types.push(this.editedItem);
          axios.post('/api/calendar/type/add', {
            name: this.editedItem.name
          })
            .catch((error) => {
              console.log(error);
            });
        }
        this.$store.dispatch('fetchTypes', {});
        this.$refs.form.resetValidation();
        this.close();
      }
    },

    close() {
      this.addTypeDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    }
  }
};
</script>

<style scoped>

</style>