<template>
    <div class="add">
        <v-container>
            <v-row class="justify-center justify-md-center align-center">
                <v-col
                        cols="12"
                        md="6"
                >

                    <v-card
                            class="ma-2"
                    >
                        <v-form
                                ref="form"
                                v-model="valid"
                                md="6"
                        >
                        <v-col>
                            <h1 class="pt-8" style="text-align: center">Biblioteka</h1>
                            <h2 class="pt-2" style="text-align: center">Dodawanie nowej książki</h2>
                        </v-col>

                            <hr>

                        <div class="upload">
                            <v-btn
                                color="#008D18"
                                class="white--text text-none pa-5 pb-0 pt-0"
                                rounded
                                depressed
                                :loading="isSelecting"
                                v-model="cover"
                                @click="onButtonClick"
                            >
                                <v-icon left>
                                    mdi-book
                                </v-icon>
                                {{ buttonText }}
                            </v-btn>
                            <input
                                ref="uploader"
                                :rules="coverRules"
                                required
                                class="d-none"
                                type="file"
                                accept="image/*"
                                @change="onFileChanged"
                            >
                        </div>

                        <v-col cols="12" style="display: inline-flex; padding-bottom: 0px">
                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="title"
                                    label="Tytuł"
                                    outlined
                                    required
                                    :rules="titleRules"
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="publish_year"
                                    label="Rok wydania"
                                    outlined
                                    required
                                    :rules="publishYearRules"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" style="display: inline-flex; padding-bottom: 0px">
                            <v-textarea
                                    class="pa-5 pb-0"
                                    v-model="isbn"
                                    label="ISBN (ENTER jako separator)"
                                    outlined
                                    required
                                    :rules="isbnRules"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12" style="display: inline-flex; padding-bottom: 0px">
                            <v-textarea
                                    class="pa-5 pb-0"
                                    v-model="description"
                                    label="Opis"
                                    outlined
                                    required
                                    :rules="descriptionRules"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12" style="display: inline-flex; padding-bottom: 0px">
                            <v-autocomplete
                                    class="pa-5 pt-0"
                                    v-model="selectedAuthor"
                                    :items="authors"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Autor"
                                    outlined
                                    required
                                    :rules="authorRules"
                                    multiple
                            >
                            <template slot="item" slot-scope="data">
                                {{data.item.name}} {{data.item.surname}}
                            </template>
                            <template slot="selection" slot-scope="data">
                                {{data.item.name}} {{data.item.surname}}
                            </template>
                            </v-autocomplete>

                            <v-autocomplete
                                    class="pa-5 pt-0"
                                    v-model="selectedCategory"
                                    :items="categories"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Kategoria"
                                    outlined
                                    required
                                    :rules="categoryRules"
                            ></v-autocomplete>

                            <v-autocomplete
                                    class="pa-5 pt-0"
                                    v-model="selectedPublisher"
                                    :items="publishers"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Wydawnictwo"
                                    outlined
                                    required
                                    :rules="publisherRules"
                            ></v-autocomplete>
                        </v-col>
                            <v-row class="pb-5 justify-center">

                                <v-btn
                                        :disabled="this.cover != null && !valid"
                                        color=#008D18
                                        class="white--text font-weight-bold mr-5 mb-6"
                                        @click="validate"
                                >
                                    Dodaj książkę
                                </v-btn>

                                <v-btn
                                        color=#808080
                                        class="white--text font-weight-bold mr-5 mb-6"
                                        @click="reset"
                                >
                                    Wyczyść dane
                                </v-btn>
                            </v-row>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  name: 'AddBook',
  data() {
    return {
      valid: false,
      defaultButtonText: 'Wgraj zdjęcie okładki (*.jpg)',
      isSelecting: false,
      value: true,
      title: '',
      isbn: '',
      description: '',
      publish_year: '',
      cover: [],
      selectedAuthor: [],
      selectedCategory: '',
      selectedPublisher: '',
      titleRules: [
        (v) => !!v || 'Pole jest wymagane!',
        (v) => v.length <= 60 || 'Tytuł jest za długi!'
      ],
      isbnRules: [
        (v) => !!v || 'Pole jest wymagane!'
      ],
      descriptionRules: [
        (v) => !!v || 'Pole jest wymagane!',
        (v) => v.length >= 25 || 'Opis jest za krótki!'
      ],
      publishYearRules: [
        (v) => !!v || 'Pole jest wymagane!',
        (v) => /^\d+$/.test(v) || 'Rok wydania musi być prawidłowy',
        (v) => v.length === 4 || 'Rok wydania powinien zawierać 4 cyfry',
        (v) => v <= new Date().getFullYear() || 'Rok wydania musi być prawidłowy'
      ],
      coverRules: [
        (v) => !!v || 'Zdjęcie okładki książki jest wymagane!',
        (v) => v.size < 2000000 || 'Zdjęcie okładki powinno być mniejsze niż 2MB!'
      ],
      authorRules: [
        (v) => !!v || 'Wymagane jest wybranie autora!'
      ],
      categoryRules: [
        (v) => !!v || 'Wymagane jest wybranie kategorii!'
      ],
      publisherRules: [
        (v) => !!v || 'Wymagane jest wybranie wydawnictwa!'
      ]
    };
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        const formData = new FormData();
        formData.append('cover', this.cover);
        formData.append('title', this.title);
        formData.append('isbn', this.isbn);
        formData.append('description', this.description);
        formData.append('publish_year', this.publish_year);
        formData.append('author', this.selectedAuthor);
        formData.append('category', this.selectedCategory);
        formData.append('publisher', this.selectedPublisher);

        const config = {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        };

        axios.post('http://127.0.0.1:8000/api/book/store', formData, config)
          .then((response) => {
            if (response.data.success == true) {
              this.$swal('Dodano', 'Pomyślnie dodano książkę do bazy bibliotecznej!', 'success');
              this.$router.push('/admin-panel/books');
            } else {
              this.$swal('Błąd', 'Coś poszło nie tak.', 'error');
            }
          })
          .catch((error) => {
            this.$swal('Błędne informacje', 'Nie można dodać książki. Spróbuj ponownie.', 'error');
            console.log(error);
          });
      }
    },
    reset() {
      this.$refs.form.reset();
    },
    onButtonClick() {
      this.isSelecting = true;
      window.addEventListener('focus', () => {
        this.isSelecting = false;
      }, { once: true });

      this.$refs.uploader.click();
    },
    onFileChanged(e) {
      this.cover = e.target.files[0];
    }
  },

  computed: {
    passwordConfirmationRule() {
      return () => this.password === this.password_confirmation || 'Hasło musi być takie same';
    },
    categories() {
      return this.$store.getters.getCategories;
    },
    authors() {
      return this.$store.getters.getAuthors;
    },
    publishers() {
      return this.$store.getters.getPublishers;
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
    this.$store.dispatch('fetchCategories', {});
    this.$store.dispatch('fetchAuthors', {});
    this.$store.dispatch('fetchPublishers', {});
  }
};
</script>

<style scoped>
    .upload {
        justify-content: center;
        text-align: center;
    }
</style>