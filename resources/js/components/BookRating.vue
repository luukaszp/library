<template>
    <v-container>
        <v-row v-if="authStatus" style="justify-content: center; margin-left: 10px; margin-right: 10px">
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

        <v-divider></v-divider>

        <v-row style="justify-content: center; margin-left: 10px; margin-right: 10px">
            <h2 style="width: 100%; text-align: center">Oceny i opinie czytelników: {{ratings.length}}</h2>
                <v-list two-line style="width: 100%">
                    <v-list-item-group>
                        <template v-for="(item,index) in ratings">
                        <v-list-item :key="item.id">
                            <v-list-item-content>
                                <v-col
                                    :cols=5
                                    style="display: inline-flex"
                                >
                                <v-list-item-title v-text="item.name + ' ' + item.surname"></v-list-item-title>
                                <v-rating
                                    v-model.number="item.rate"
                                    background-color="orange lighten-3"
                                    color="orange"
                                    medium
                                    readonly
                                ></v-rating>
                                </v-col>
                                <v-list-item-subtitle style="padding-top: 10px; margin-left: 10px" class="text--primary" v-text="opinions[index].opinion"></v-list-item-subtitle>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-list-item-action-text v-text="item.created_at"></v-list-item-action-text>
                            </v-list-item-action>
                        </v-list-item>

                        <v-divider :key="item.id"></v-divider>

                        </template>
                    </v-list-item-group>
                </v-list>
        </v-row>
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
    items: [],
    rating: 0,
    valid: false,
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
    opinions() {
      return this.$store.getters.getOpinions;
    }
  },

  created () {
    this.$store.dispatch('fetchRatings', this.book_id);
    this.$store.dispatch('fetchOpinions', this.book_id);
  },

  methods: {
    addOpinion() {
      if (this.$refs.form.validate()) {
        axios.post('/api/rating/add', {
          rate: this.rating,
          book_id: parseInt(this.book_id)
        })
          .catch((error) => {
            console.log(error);
          });
          axios.post('/api/opinion/add', {
            opinion: this.opinion,
            book_id: parseInt(this.book_id)
          })
            .catch((error) => {
              console.log(error);
            });
      }
      this.$store.dispatch('fetchRatings', this.book_id);
      this.$store.dispatch('fetchOpinions', this.book_id);
    }
  }
};
</script>