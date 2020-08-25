<template>
            <v-data-table
                    :headers="headers"
                    :items="authors"
                    :search="search"
                    sort-by="amount"
                    class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Autorzy</v-toolbar-title>
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
                        <v-dialog v-model="addAuthorDialog" max-width="300px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="#3eb4a7" dark class="mb-2" v-on="on">Nowy autor</v-btn>
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
                                                    <v-text-field v-model="editedItem.name" :rules="nameRules" label="Imię"></v-text-field>
                                                </v-col>
                                                <v-col>
                                                    <v-text-field v-model="editedItem.surname" :rules="surnameRules" label="Nazwisko"></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-form>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                                    <v-btn color="blue darken-1" text @click="addAuthor" :disabled="!valid">Zapisz</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.action`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editAuthor(item)"
                        >
                        mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteAuthor(item)"
                        >
                        mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak autorów</p>
                    </template>

            </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  name: 'Authors',
  data: () => ({
    addAuthorDialog: false,
    valid: false,
    search: '',
    authorName: '',
    authorSurname: '',
    headers: [
      {
        text: 'Imię',
        align: 'start',
        value: 'name'
      },
      { text: 'Nazwisko', value: 'surname' },
      { text: 'Akcje', value: 'action', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      surname: ''
    },
    defaultItem: {
      name: '',
      surname: ''
    },
    nameRules: [
      (v) => !!v || 'Imię jest wymagane!',
      (v) => /^[a-zA-Z]+$/.test(v) || 'Imię powinno zawierać tylko litery'
    ],
    surnameRules: [
      (v) => !!v || 'Nazwisko jest wymagane!',
      (v) => /^[a-zA-Z]+$/.test(v) || 'Nazwisko powinno zawierać tylko litery'
    ]
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nowy autor' : 'Edytuj dane autora';
    },
    authors() {
      return this.$store.getters.getAuthors;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.$store.dispatch('fetchAuthors', {});
  },

  methods: {
    editAuthor(item) {
      this.editedIndex = this.authors.indexOf(item);
      this.editedItem = { ...item };
      this.addAuthorDialog = true;
    },

    deleteAuthor(item) {
      const index = this.authors.indexOf(item);
      if (confirm('Czy jesteś pewien, że chcesz usunąć tego autora?')) {
        axios.delete(`/api/author/delete/${item.id}`, {});
        this.authors.splice(index, 1);
      }
    },

    addAuthor() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.authors[this.editedIndex], this.editedItem);
          axios.put(`/api/author/edit/${this.editedItem.id}`, {
            name: this.editedItem.name,
            surname: this.editedItem.surname
          });
        } else {
          this.authors.push(this.editedItem);
          axios.post('/api/author/add', {
            name: this.editedItem.name,
            surname: this.editedItem.surname
          })
            .catch((error) => {
              console.log(error);
            });
        }
        this.close();
      }
    },

    close() {
      this.addAuthorDialog = false;
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