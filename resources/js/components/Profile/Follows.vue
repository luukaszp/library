<template>
    <v-row style="justify-content: center; text-align: center">
        <v-col md=12 style="padding-top: 25px;" v-if="this.follows">
            <h1>Obserwowani autorzy</h1>
            <v-list-item :items="follows">
                <v-list-item-content>
                <v-list-item-title v-text="follows.name + ' ' + follows.surname"></v-list-item-title>
                </v-list-item-content>

                <v-list-item-action>
                <v-btn icon>
                    <v-icon color="grey lighten-1" @click="removeAuthor(follows)">mdi-close</v-icon>
                </v-btn>
                </v-list-item-action>
            </v-list-item>
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