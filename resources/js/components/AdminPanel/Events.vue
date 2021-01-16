<template>
            <v-data-table
                    :headers="headers"
                    :items="events"
                    :search="search"
                    sort-by="amount"
                    class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Wydarzenia</v-toolbar-title>
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
                        <v-dialog v-model="addEventDialog" max-width="350px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="#008D18" dark class="mb-2" v-on="on">Nowe wydarzenie</v-btn>
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
                                                    <v-text-field v-model="editedItem.name" :rules="nameRules" label="Wydarzenie"></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-menu
                                                        v-model="menu1"
                                                        :close-on-content-click="false"
                                                        :nudge-right="40"
                                                        transition="scale-transition"
                                                        offset-y
                                                        min-width="290px"
                                                >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field
                                                    v-model="editedItem.date"
                                                    label="Wybierz datę wydarzenia"
                                                    prepend-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    :rules="dateRules"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker v-model="editedItem.date" @input="menu1 = false"></v-date-picker>
                                                </v-menu>
                                            </v-row>
                                            <v-row>
                                                <v-menu
                                                        v-model="menu2"
                                                        :close-on-content-click="false"
                                                        :nudge-right="40"
                                                        transition="scale-transition"
                                                        offset-y
                                                        min-width="290px"
                                                >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field
                                                    v-model="editedItem.time"
                                                    label="Wybierz czas wydarzenia"
                                                    prepend-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    :rules="timeRules"
                                                    ></v-text-field>
                                                </template>
                                                <v-time-picker v-model="editedItem.time" @input="menu2 = false" format="24hr"></v-time-picker>
                                                </v-menu>
                                            </v-row>
                                            <v-row>
                                                <v-autocomplete
                                                    v-model="selectedType"
                                                    :items="types"
                                                    item-text="name"
                                                    item-value="id"
                                                    menu-props="auto"
                                                    label="Typ"
                                                    outlined
                                                    required
                                                    :rules="typeRules"
                                                >
                                                </v-autocomplete>
                                            </v-row>
                                        </v-form>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                                    <v-btn color="blue darken-1" text @click="addEvent" :disabled="!valid">Zapisz</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.action`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editEvent(item)"
                        >
                        mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteEvent(item)"
                        >
                        mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak wydarzeń</p>
                    </template>

            </v-data-table>
</template>

<script>
/* eslint-disable */
import axios from 'axios';

export default {
  name: 'Events',
  data: () => ({
    addEventDialog: false,
    valid: false,
    menu1: false,
    menu2: false,
    selectedType: '',
    search: '',
    eventName: '',
    headers: [
      {
        text: 'Wydarzenia',
        align: 'start',
        value: 'name'
      },
      { text: 'Data', value: 'date' },
      { text: 'Czas', value: 'time' },
      { text: 'Typ', value: 'typeName' },
      { text: 'Akcje', value: 'action', sortable: false }
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      date: '',
      time: ''
    },
    defaultItem: {
      name: '',
      date: '',
      time: ''
    },
    nameRules: [
      (v) => !!v || 'Nazwa jest wymagana!',
    ],
    dateRules: [
      (v) => !!v || 'Data wydarzenia jest wymagana'
    ],
    timeRules: [
      (v) => !!v || 'Czas wydarzenia jest wymagany'
    ],
    typeRules: [
      (v) => !!v || 'Typ wydarzenia jest wymagany!',
    ],
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nowe wydarzenie' : 'Edytuj wydarzenie';
    },
    events() {
      return this.$store.getters.getEvents;
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
    this.$store.dispatch('fetchEvents', {});
  },

  methods: {
    editEvent(item) {
      this.editedIndex = this.events.indexOf(item);
      this.editedItem = { ...item };
      this.addEventDialog = true;
    },

    deleteEvent(item) {
      const index = this.events.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć te wydarzenie?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj',
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/calendar/event/delete/${item.id}`, {});
          this.events.splice(index, 1);
          this.$swal('Usunięto', 'Pomyślnie usunięto wydarzenie', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    addEvent() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.events[this.editedIndex], this.editedItem);
          axios.put(`/api/calendar/event/edit/${this.editedItem.id}`, {
            name: this.editedItem.name,
            date: this.editedItem.date,
            time: this.editedItem.time,
            type_id: this.selectedType
          });
        } else {
          this.events.push(this.editedItem);
          axios.post('/api/calendar/event/add', {
            name: this.editedItem.name,
            date: this.editedItem.date,
            time: this.editedItem.time,
            type_id: this.selectedType
          })
            .catch((error) => {
              console.log(error);
            });
        }
        this.$store.dispatch('fetchEvents', {});
        this.$refs.form.resetValidation();
        this.$refs.form.reset();
        this.close();
      }
    },

    close() {
      this.addEventDialog = false;
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