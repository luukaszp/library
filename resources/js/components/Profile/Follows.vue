<template>
    <v-row style="justify-content: center; text-align: center">
        <v-col md=12>
            <h1>Obserwowani autorzy</h1>
            <v-list-item
                v-for="folAuthor in this.followedAuthors"
                :key="folAuthor.name"
            >
                <v-list-item-content>
                <v-list-item-title v-text="folAuthor.name + ' ' + folAuthor.surname"></v-list-item-title>
                </v-list-item-content>

                <v-list-item-action>
                <v-btn icon>
                    <v-icon color="grey lighten-1" @click="removeAuthor(folAuthor)">mdi-close</v-icon>
                </v-btn>
                </v-list-item-action>
            </v-list-item>

            <v-list-item
                v-if="!this.followedAuthors.length"
            >
            <v-list-item-content>
                <v-list-item-title>Brak</v-list-item-title>
            </v-list-item-content>
            </v-list-item>
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

  async created () {
    await this.getFollowedAuthors();
  },

  methods: {
    async getFollowedAuthors() {
      await axios
        .get(`api/follow/getFollowedAuthors/${this.user_id}`)
        .then((response) => {
          this.followedAuthors = response.data;
        })
    },

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
        this.getFollowedAuthors();
      });
    }
  }
};
</script>