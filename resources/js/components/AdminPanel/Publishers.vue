<template>
            <v-data-table
                    :headers="headers"
                    :items="publishers"
                    :search="search"
                    sort-by="amount"
                    class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Wydawnictwa</v-toolbar-title>
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
                        <v-dialog v-model="addPublisherDialog" max-width="300px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="#3eb4a7" dark class="mb-2" v-on="on">Nowe wydawnictwo</v-btn>
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
                                                    <v-text-field v-model="editedItem.name" :rules="publisherRules" label="Wydawnictwo"></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-form>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                                    <v-btn color="blue darken-1" text @click="addPublisher" :disabled="!valid">Zapisz</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.action`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editPublisher(item)"
                        >
                        mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deletePublisher(item)"
                        >
                        mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak wydawnictw</p>
                    </template>

            </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  name: 'Publishers',
  data: () => ({
    addPublisherDialog: false,
    valid: false,
    search: '',
    publisherName: '',
    headers: [
      {
        text: 'Wydawnictwa',
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
    publisherRules: [
      (v) => !!v || 'Nazwa jest wymagana!',
    ]
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nowe wydawnictwo' : 'Edytuj wydawnictwo';
    },
    publishers() {
      return this.$store.getters.getPublishers;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.$store.dispatch('fetchPublishers', {});
  },

  methods: {
    editPublisher(item) {
      this.editedIndex = this.publishers.indexOf(item);
      this.editedItem = { ...item };
      this.addPublisherDialog = true;
    },

    deletePublisher(item) {
      const index = this.publishers.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć te wydawnictwo?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj',
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/publisher/delete/${item.id}`, {});
          this.publishers.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto wydawnictwo', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    addPublisher() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.publishers[this.editedIndex], this.editedItem);
          axios.put(`/api/publisher/edit/${this.editedItem.id}`, {
            name: this.editedItem.name
          });
        } else {
          this.publishers.push(this.editedItem);
          axios.post('/api/publisher/add', {
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
              title: 'Dodano wydawnictwo!'
            });
          })
            .catch((error) => {
              console.log(error);
            });
        }
        this.$store.dispatch('fetchPublishers', {});
        this.$refs.form.resetValidation();
        this.close();
      }
    },

    close() {
      this.addPublisherDialog = false;
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