<template>
    <v-container>
        <v-row v-if="loggedUser.card_number && check === false" style="justify-content: center; margin-left: 10px; margin-right: 10px">
            <h2 style="width: 100%; text-align: center">Oceń książkę: </h2>
        <v-form v-model="valid" ref="form" style="width: 100%; text-align: center">
            <v-rating
                v-model="rating"
                background-color="orange lighten-3"
                color="orange"
                medium
                :rules="ratingRules"
            ></v-rating>

            <v-col cols="12">
                <v-textarea
                    v-model="opinion"
                    label="Napisz opinię"
                    auto-grow
                    outlined
                    rows="3"
                    row-height="25"
                    :rules="opinionRules"
                ></v-textarea>
            </v-col>
        </v-form>
            <div class="mb-5">
                <v-btn large color="success" @click="addOpinion" :disabled="this.rating == 0">Wystaw opinię</v-btn>
            </div>
        </v-row>

        <v-row v-if="loggedUser && check === true" style="justify-content: center; margin: 25px 10px">
            <h2 style="width: 100%; text-align: center">Serdecznie dziękujemy za wystawienie opinii!</h2>
            <v-icon x-large>mdi-emoticon-excited</v-icon>
        </v-row>

        <v-row v-if="!loggedUser" style="justify-content: center; margin: 25px 10px">
            <h2 style="width: 100%; text-align: center">Aby wystawić opinię należy się zalogować!</h2>
        </v-row>

        <v-divider></v-divider>

        <v-row style="justify-content: center; margin-left: 10px; margin-right: 10px">
            <h2 style="width: 100%; text-align: center; padding-top: 20px">Oceny i opinie czytelników: {{ratings.length}}</h2>
                <v-list two-line style="width: 100%">
                    <v-list-item-group>
                        <template v-for="(item) in ratings">
                        <v-list-item :key="item.id +- item.rate">
                            <v-list-item-content>
                                <v-col
                                    :cols=8
                                    style="display: inline-flex"
                                >
                                <v-list-item-avatar>
                                    <v-img :src="'https://storageforlibrary.blob.core.windows.net/library/' + item.avatar"></v-img>
                                </v-list-item-avatar>
                                <v-list-item-title v-text="item.name + ' ' + item.surname"></v-list-item-title>
                                <v-rating
                                    :value="parseInt(item.rate)"
                                    background-color="orange lighten-3"
                                    color="orange"
                                    medium
                                    readonly
                                    style="padding-top: 15px"
                                ></v-rating>
                                </v-col>
                                <v-list-item-subtitle style="padding-top: 10px; margin-left: 10px" class="text--primary" v-text="item.opinion"></v-list-item-subtitle>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-list-item-action-text v-text="item.created_at"></v-list-item-action-text>
                                <div class="action">
                                  <v-icon
                                      v-if="item.user_id === loggedUser.id"
                                      class="mr-2"
                                      @click="editOpinion(item)"
                                  >
                                  mdi-pencil
                                  </v-icon>
                                  <v-icon
                                      v-if="(item.user_id === loggedUser.id) || (loggedUser.id_number)"
                                      @click="deleteOpinion(item)"
                                  >
                                  mdi-delete
                                  </v-icon>
                                </div>
                            </v-list-item-action>
                        </v-list-item>

                        <v-divider :key="item.id"></v-divider>

                        </template>
                    </v-list-item-group>
                </v-list>
        </v-row>

        <v-dialog v-model="editOpinionDialog" max-width="400px">
          <v-card  style="text-align: center">
            <v-card-title style="justify-content: center">
              <span class="headline">Edytuj swoją opinię</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-form v-model="valid" ref="form">
                  <v-row style="display: inline-block">
                    <v-rating
                        v-model="editedItem.rate"
                        background-color="orange lighten-3"
                        color="orange"
                        medium
                        :rules="ratingRules"
                    ></v-rating>
                    <v-col>
                      <v-textarea
                        v-model="editedItem.opinion"
                        label="Napisz opinię"
                        auto-grow
                        outlined
                        rows="3"
                        row-height="25"
                        style="width: 300px"
                        :rules="opinionRules"
                      ></v-textarea>
                    </v-col>
                  </v-row>
                </v-form>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="#008D18" text @click="close">Anuluj</v-btn>
              <v-btn color="#008D18" text @click="addOpinion" :disabled="!valid">Zapisz</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
/*eslint-disable*/
import axios from 'axios';

export default {
  name: 'BookRating',
  props: ['book_id'],
  data: () => ({
    opinion: '',
    rating: 0,
    valid: false,
    editOpinionDialog: false,
    editedIndex: -1,
    editedItem: {
      opinion: '',
      rate: ''
    },
    defaultItem: {
      opinion: '',
      rate: ''
    },
    ratingRules: [
      (v) => !!v || 'Ocena jest wymagana!'
    ],
    opinionRules: [
      (v) => v.length <= 500 || 'Opinia jest za długa!'
    ]
  }),

  computed: {
    authStatus() {
      return this.$store.getters.authStatus;
    },
    ratings() {
      return this.$store.getters.getRatings;
    },
    loggedUser() {
      return this.$store.getters.loggedUser;
    },
    check() {
      if(this.ratings.length != 0) {
        for (let i = 0; i < this.ratings.length; i++) {
          if(this.ratings[i].user_id === this.loggedUser.id) {
            return true
          }
        }
      }
      return false
    }
  },

  created () {
    this.$store.dispatch('fetchAverage', this.book_id);
    this.$store.dispatch('fetchRatings', this.book_id);
  },

  methods: {
    addOpinion() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.ratings[this.editedIndex], this.editedItem);
          axios.put(`/api/rating/edit/${this.editedItem.id}`, {
            opinion: this.editedItem.opinion,
            rate: this.editedItem.rate,
            book_id: parseInt(this.book_id)
          });
        } else {
        axios.post('/api/rating/add', {
          rate: this.rating,
          opinion: this.opinion,
          book_id: parseInt(this.book_id)
        })
          .catch((error) => {
            console.log(error);
          });
        }
      }
      this.$store.dispatch('fetchAverage', this.book_id);
      this.$store.dispatch('fetchRatings', this.book_id);
      this.close();
    },

    editOpinion(item) {
      this.editedIndex = this.ratings.indexOf(item);
      this.editedItem = { ...item };
      this.editOpinionDialog = true;
    },

    deleteOpinion(item) {
      const index = this.ratings.indexOf(item);
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tę opinię?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj',
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/rating/delete/${item.id}`, {});
          this.ratings.splice(index, 1);
          this.$store.dispatch('fetchAverage', this.book_id);
          this.$store.dispatch('fetchRatings', this.book_id);
          this.$swal('Usunięto', 'Pomyślnie usunięto opinię', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
      });
    },

    close() {
      this.editOpinionDialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    }
  }
};
</script>