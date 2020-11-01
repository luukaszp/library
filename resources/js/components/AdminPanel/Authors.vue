<template>
            <v-data-table
                :headers="headers"
                :items="authors"
                :search="search"
                :expanded.sync="expanded"
                show-expand
                :single-expand="true"
                sort-by="amount"
                class="elevation-1"
            >

            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">
                    Życiorys autora: {{ item.description }}
                </td>
            </template>

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
                        <v-dialog v-model="addAuthorDialog" max-width="500px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="#3eb4a7" dark class="mb-2" v-on="on">Nowy autor</v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-form ref="form">
                                            <v-row>
                                                <v-col>
                                                    <v-text-field v-model="editedItem.name" :rules="nameRules" label="Imię"></v-text-field>
                                                </v-col>
                                                <v-col>
                                                    <v-text-field v-model="editedItem.surname" :rules="surnameRules" label="Nazwisko"></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col>
                                                    <v-text-field v-model="editedItem.description" :rules="descriptionRules" label="Życiorys"></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row style="justify-content: center; text-align: center">
                                                <v-btn
                                                    color="primary"
                                                    class="text-none pa-5 pb-0 pt-0"
                                                    rounded
                                                    depressed
                                                    :loading="isSelecting"
                                                    v-model="photo"
                                                    @click="onButtonClick"
                                                >
                                                    <v-icon left>
                                                        mdi-book
                                                    </v-icon>
                                                    {{ buttonText }}
                                                </v-btn>
                                                <input
                                                    ref="uploader"
                                                    :rules="photoRules"
                                                    required
                                                    class="d-none"
                                                    type="file"
                                                    accept="image/*"
                                                    @change="onFileChanged"
                                                >
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

                        <v-dialog v-model="editPhotoDialog" max-width="550px" class="rounded-xl">
                            <v-card>
                                <v-card-text>
                                    <v-container>
                                        <v-row align="center" justify="center">
                                            <v-col cols="6">
                                            <v-card>
                                                <v-img
                                                v-bind:src="('../storage/' + image)"
                                                aspect-ratio="1"
                                                ></v-img>
                                            </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-divider></v-divider>

                                <v-card-actions style="justify-content: center; display: block ruby; text-align: center; padding-bottom: 25px">
                                    <v-spacer></v-spacer>
                                    <v-btn color="#228B22" text @click="closePhoto">Anuluj</v-btn>
                                    <v-btn
                                        color="primary"
                                        class="text-none pl-5 pr-5"
                                        style="margin-right: 15px"
                                        rounded
                                        depressed
                                        :loading="isSelecting"
                                        v-model="photo"
                                        @click="onButtonClick"
                                    >
                                        <v-icon left>
                                            mdi-book
                                        </v-icon>
                                        {{ buttonText }}
                                    </v-btn>
                                    <input
                                        ref="uploader"
                                        :rules="photoRules"
                                        required
                                        class="d-none"
                                        type="file"
                                        accept="image/*"
                                        @change="onFileChanged"
                                    >
                                    <v-btn
                                    color=#228B22
                                    @click="sendPhoto"
                                    :disabled="!valid"
                                    text
                                    >
                                    Wgraj
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.photo`]="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="showPhoto(item)"
                    >
                        mdi-image
                    </v-icon>
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
    editPhotoDialog: false,
    defaultButtonText: 'Zmień zdjęcie autora',
    isSelecting: false,
    valid: false,
    search: '',
    image: '',
    photo: [],
    expanded: [],
    headers: [
      {
        text: 'Imię',
        align: 'start',
        value: 'name'
      },
      { text: 'Nazwisko', value: 'surname' },
      { text: 'Zdjęcie', value: 'photo' },
      { text: 'Akcje', value: 'action', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      surname: '',
      description: ''
    },
    defaultItem: {
      name: '',
      surname: '',
      description: ''
    },
    nameRules: [
      (v) => !!v || 'Imię jest wymagane!',
      (v) => /^[a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]+$/.test(v) || 'Imię powinno zawierać tylko litery'
    ],
    surnameRules: [
      (v) => !!v || 'Nazwisko jest wymagane!',
      (v) => /^[a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]+$/.test(v) || 'Nazwisko powinno zawierać tylko litery'
    ],
    descriptionRules: [
      (v) => !!v || 'Życiorys jest wymagany!',
      (v) => v.length >= 25 || 'Życiorys jest za krótki!'
    ],
    photoRules: [
      (v) => !!v || 'Zdjęcie autora jest wymagane!',
      (v) => v.size < 2000000 || 'Zdjęcie autora powinno być mniejsze niż 2MB!'
    ]
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nowy autor' : 'Edytuj dane autora';
    },
    authors() {
      return this.$store.getters.getAuthors;
    },
    buttonText() {
      return this.selectedFile ? this.selectedFile.name : this.defaultButtonText;
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
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tego autora?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj'
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/author/delete/${item.id}`, {});
          this.authors.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto autora', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    addAuthor() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.authors[this.editedIndex], this.editedItem);
          axios.put(`/api/author/edit/${this.editedItem.id}`, {
            name: this.editedItem.name,
            surname: this.editedItem.surname,
            description: this.editedItem.description
          });
        } else {
          const formData = new FormData();
          formData.append('photo', this.photo);
          formData.append('name', this.editedItem.name);
          formData.append('surname', this.editedItem.surname);
          formData.append('description', this.editedItem.description);

          const config = {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          };

          axios.post('http://127.0.0.1:8000/api/author/add', formData, config)
          .then(() => {
            const Toast = this.$swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', this.$swal.stopTimer);
                toast.addEventListener('mouseleave', this.$swal.resumeTimer);
              }
            });

            Toast.fire({
              icon: 'success',
              title: 'Dodano autora!'
            });
          })
            .catch((error) => {
              console.log(error);
            });
        }
        this.$store.dispatch('fetchAuthors', {});
        this.$refs.form.resetValidation();
        this.close();
      }
    },

    close() {
      this.addAuthorDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    onButtonClick() {
      this.isSelecting = true;
      window.addEventListener('focus', () => {
        this.isSelecting = false;
      }, { once: true });

      this.$refs.uploader.click();
    },
    onFileChanged(e) {
      this.photo = e.target.files[0];
      this.valid = true;
    },

    closePhoto () {
      this.editPhotoDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    showPhoto (item) {
      this.image = item.photo;
      this.editedIndex = this.authors.indexOf(item);
      this.editedItem = { ...item };
      this.editPhotoDialog = true;
    },

    sendPhoto () {
      const formData = new FormData();
      formData.append('photo', this.photo);

      const config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      };
      axios.post(`/api/author/changePhoto/${this.editedItem.id}`, formData, config)
        .then((response) => {
          if (response.data.success === true) {
            this.$swal('Dodano', 'Pomyślnie zmieniono awatar autora!', 'success');
            this.editPhotoDialog = false;
          } else {
            this.$swal('Błąd', 'Awatar autora nie mógł zostać zmieniony!', 'error');
          }
        });
      this.$store.dispatch('fetchAuthors', {});
    }
  }
};
</script>

<style scoped>

</style>