<template>
            <v-data-table
                    :headers="headers"
                    :items="categories"
                    :search="search"
                    sort-by="amount"
                    class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Kategorie</v-toolbar-title>
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
                        <v-dialog v-model="addCategoryDialog" max-width="300px" persistent>
                            <template v-slot:activator="{ on }">
                                <v-btn color="#008D18" dark class="mb-2" v-on="on">Nowa kategoria</v-btn>
                            </template>
                            <v-card>
                                <v-card-title style="justify-content: center">
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-form v-model="valid" ref="form">
                                            <v-row>
                                                <v-col>
                                                    <v-text-field v-model="editedItem.name" :rules="categoryRules" label="Kategoria"></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-form>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="#008D18" text @click="close">Anuluj</v-btn>
                                    <v-btn color="#008D18" text @click="addCategory" :disabled="!valid">Zapisz</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.action`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editCategory(item)"
                        >
                        mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteCategory(item)"
                        >
                        mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak kategorii</p>
                    </template>

            </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  name: 'Categories',
  data: () => ({
    addCategoryDialog: false,
    valid: false,
    search: '',
    categoryName: '',
    headers: [
      {
        text: 'Kategorie',
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
    categoryRules: [
      (v) => !!v || 'Nazwa jest wymagana!',
      (v) => /^[a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]+$/.test(v) || 'Nazwa kategorii powinna zawierać tylko litery'
    ]
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nowa kategoria' : 'Edytuj kategorie';
    },
    categories() {
      return this.$store.getters.getCategories;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.$store.dispatch('fetchCategories', {});
  },

  methods: {
    editCategory(item) {
      this.editedIndex = this.categories.indexOf(item);
      this.editedItem = { ...item };
      this.addCategoryDialog = true;
    },

    deleteCategory(item) {
      const index = this.categories.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tę kategorię?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj',
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/category/delete/${item.id}`, {});
          this.categories.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto kategorię', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    addCategory() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.categories[this.editedIndex], this.editedItem);
          axios.put(`/api/category/edit/${this.editedItem.id}`, {
            name: this.editedItem.name
          });
        } else {
          this.categories.push(this.editedItem);
          axios.post('/api/category/add', {
            name: this.editedItem.name
          })
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
              title: 'Dodano kategorię!'
            });
          })
            .catch((error) => {
              console.log(error);
            });
        }
        this.$store.dispatch('fetchCategories', {});
        this.$refs.form.resetValidation();
        this.close();
      }
    },

    close() {
      this.addCategoryDialog = false;
      this.$refs.form.resetValidation();
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