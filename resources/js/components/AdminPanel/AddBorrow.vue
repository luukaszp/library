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
                            max-width="600"
                    >
                        <v-form
                                ref="form"
                                v-model="valid"
                                md="6"
                        >
                            <h1 class="pt-8" style="text-align: center">Biblioteka</h1>
                            <h2 class="pt-2" style="text-align: center">Wypożyczanie książek</h2>

                            <hr>

                            <v-autocomplete
                                    class="pa-5 pb-0"
                                    v-model="selectedReader"
                                    :items="readers"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Czytelnik"
                                    outlined
                                    required
                                    :rules="readerRules"
                            >
                            <template slot="item" slot-scope="data">
                                {{data.item.name}} {{data.item.surname}}
                            </template>
                            <template slot="selection" slot-scope="data">
                                {{data.item.name}} {{data.item.surname}}
                            </template>
                            </v-autocomplete>

                            <v-autocomplete
                                    class="pa-5 pb-0 pt-0"
                                    v-model="selectedBooks"
                                    :items="books"
                                    item-text="title"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Książki"
                                    outlined
                                    required
                                    :rules="booksRules"
                                    multiple
                            >
                            <template slot="item" slot-scope="data">
                                Tytuł: {{data.item.title}}, ISBN: {{data.item.isbn}}
                            </template>
                            <template slot="selection" slot-scope="data">
                                {{data.item.title}}
                            </template>
                            </v-autocomplete>

                            <v-menu
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                            >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                v-model="date"
                                label="Wybierz datę wypożyczenia"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                :rules="dateRules"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="date" @input="menu = false"></v-date-picker>
                            </v-menu>

                            <v-row class="pb-5 justify-center">

                                <v-btn
                                        :disabled="!valid"
                                        color=#008D18
                                        class="white--text font-weight-bold mr-5 mb-6"
                                        @click="validate"
                                >
                                    Wypożycz książkę
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
export default {
  name: 'AddBorrow',
  data() {
    return {
      valid: false,
      value: true,
      selectedReader: '',
      isbn: '',
      selectedBooks: [],
      date: '',
      menu: false,
      readerRules: [
        (v) => !!v || 'Wybranie czytelnika jest wymagane!'
      ],
      booksRules: [
        (v) => !!v || 'Wymagane jest wybranie co najmniej jednej książki!',
        (v) => v.length <= 5 || 'Maksymalnie można wybrać 5 książek'
      ],
      dateRules: [
        (v) => !!v || 'Wymagane jest wybranie daty!'
      ],
      isbnRules: [
        (v) => !!v || 'Pole jest wymagane!'
      ],
    };
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        this.$store.dispatch('borrowBooks', {
          reader_id: this.selectedReader,
          book_id: this.selectedBooks,
          borrows_date: this.date,
          isbn: this.isbn
        })
        .then((response) => {
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
              title: 'Wypożyczono książkę!'
            });
          })
          .catch((error) => {
            this.$swal('Błędny ISBN', 'Podano zły numer ISBN. Spróbuj ponownie.', 'error');
            console.log(error);
          });
      }
    },
    reset() {
      this.$refs.form.reset();
    }
  },

  computed: {
    readers() {
      return this.$store.getters.getReaders;
    },
    books() {
      return this.$store.getters.getBooks;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.$store.dispatch('fetchReaders', {});
    this.$store.dispatch('fetchAvailableBooks', {});
  }
};
</script>

<style scoped>

</style>