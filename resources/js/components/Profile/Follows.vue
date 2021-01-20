<template>
    <v-row style="justify-content: center; text-align: center">
        <v-col md=12 style="padding-top: 25px;" v-if="this.follows">
            <v-card
                class="mx-auto"
                max-width="400"
                tile
            >
                <v-list rounded>
                <v-subheader>Obserwowani autorzy</v-subheader>
                <v-list-item-group>
                    <v-list-item :items="follows">
                        <v-list-item-icon>
                            <v-icon>mdi-account</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <router-link v-if="follows.id" :to="{ name: 'authorview', params: { author_id: follows.id } }" style="text-decoration: none; color: #008D18">
                                <v-list-item-title style="font-weight: bold" v-text="follows.name + ' ' + follows.surname"></v-list-item-title>
                            </router-link>
                        </v-list-item-content>

                        <v-list-item-action>
                        <v-btn x-large icon>
                            <v-icon color="red lighten-1" @click="removeAuthor(follows)">mdi-close</v-icon>
                        </v-btn>
                        </v-list-item-action>
                    </v-list-item>
                </v-list-item-group>
                </v-list>
            </v-card>
        </v-col>
        <v-col v-else style="padding-top: 25px;">
            <h1>Obserwowani autorzy: brak</h1>
        </v-col>
    </v-row>
</template>

<script>
/*eslint-disable*/
import axios from 'axios';

export default {
  name: 'Follows',
  props: ['user_id'],

  data: () => ({
    followedAuthors: []
  }),

  computed: {
    follows() {
      return this.$store.getters.getFollows;
    }
  },

  created () {
    this.$store.dispatch('fetchFollowedAuthors', this.user_id);
  },

  methods: {
    removeAuthor(folAuthor) {
      this.$swal({
        title: 'Czy jesteś pewien, że chcesz usunąć tego autora z listy obserwowanych?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Usuń',
        cancelButtonText: 'Anuluj'
      }).then((result) => {
        if (result.value) {
          axios.delete(`/api/follow/delete/${folAuthor.id}/author`, {});
          this.$swal('Usunięto', 'Pomyślnie usunięto autora z listy obserwowanych', 'success');
        } else {
          this.$swal('Anulowano', 'Akcja została anulowana', 'info');
        }
        this.$store.dispatch('fetchFollowedAuthors', this.user_id);
      });
    }
  }
};
</script>