<template>
    <v-container>
        <v-row style="justify-content: center; margin-left: 10px; margin-right: 10px">
            <h2 style="width: 100%; text-align: center">Oceń książkę: </h2>

        <v-form v-model="valid" ref="form" style="width: 100%; text-align: center">
            <v-rating
                v-model="rating"
                background-color="orange lighten-3"
                color="orange"
                medium
            ></v-rating>

            <v-col cols="12">
                <v-textarea
                    label="Napisz opinię"
                    auto-grow
                    outlined
                    rows="3"
                    row-height="25"
                ></v-textarea>
            </v-col>
        </v-form>

            <div class="mb-5">
                <v-btn large color="success" dark @click="addOpinion" :disabled="!valid">Wystaw opinię</v-btn>
            </div>

        </v-row>
            <v-divider></v-divider>

        <v-row style="justify-content: center; margin-left: 10px; margin-right: 10px">
            <h2>Oceny i opinie czytelników (ilośc)</h2>
                <v-list two-line>
                    <v-list-item-group>
                        <template v-for="(item, index) in items" v-bind:disabled="lockSelection">
                        <v-list-item :key="item.title">
                            <v-list-item-content>
                                <v-list-item-title v-text="item.title"></v-list-item-title>
                                <v-list-item-subtitle class="text--primary" v-text="item.headline"></v-list-item-subtitle>
                                <v-list-item-subtitle v-text="item.subtitle"></v-list-item-subtitle>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-list-item-action-text v-text="item.action"></v-list-item-action-text>
                            </v-list-item-action>
                        </v-list-item>

                        <v-divider
                            v-if="index + 1 < items.length"
                            :key="index"
                        ></v-divider>
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

    data: () => ({
    rating: null,
    lockSelection: true,
    valid: false,
    items: [
        {
          action: '15 min',
          headline: 'Brunch this weekend?',
          title: 'Ali Connors',
          subtitle: "I'll be in your neighborhood doing errands this weekend. Do you want to hang out?",
        },
        {
          action: '2 hr',
          headline: 'Summer BBQ',
          title: 'me, Scrott, Jennifer',
          subtitle: "Wish I could come, but I'm out of town this weekend.",
        },
        {
          action: '6 hr',
          headline: 'Oui oui',
          title: 'Sandra Adams',
          subtitle: 'Do you have Paris recommendations? Have you ever been?',
        },
        {
          action: '12 hr',
          headline: 'Birthday gift',
          title: 'Trevor Hansen',
          subtitle: 'Have any ideas about what we should get Heidi for her birthday?',
        },
        {
          action: '18hr',
          headline: 'Recipe to try',
          title: 'Britta Holt',
          subtitle: 'We should eat this: Grate, Squash, Corn, and tomatillo Tacos.',
        },
      ],
    }),

  computed: {

  },

  created () {
  },

  methods: {
      addOpinion() {
      if (this.$refs.form.validate()) {
          this.categories.push(this.editedItem);
          axios.post('/api/category/add', {
            name: this.editedItem.name
          })
            .catch((error) => {
              console.log(error);
            });
        this.$store.dispatch('fetchCategories', {});
        this.close();
      }
    },
  }
};
</script>