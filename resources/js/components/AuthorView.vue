<template>
    <v-container>
        <v-row style="margin-left: 10px; margin-right: 10px">
            <v-col md=3 style="justify-content: center; text-align: center;">
                <v-avatar size="200">
                    <v-img class="card-img" v-bind:src="('../storage/' + authors.photo)"></v-img>
                </v-avatar>
            </v-col>

            <v-col style="text-align: left">
                <h2>{{authors.name + ' ' + authors.surname}}</h2>
                <v-divider></v-divider>
                <p>{{authors.description}}</p>

                <v-btn
                    v-if="loggedUser.card_number"
                    outlined
                    color="#008D18"
                    @click="followAuthor()"
                >
                    DODAJ DO ULUBIONYCH
                    <v-icon style="padding-left: 10px" color="#FFD700">mdi-star</v-icon>
                </v-btn>
            </v-col>
        </v-row>

        <v-divider></v-divider>

        <v-row style="margin-left: 10px; margin-right: 10px; justify-content: center;">
            <AuthorBooks v-bind:author_id="author_id"/>
        </v-row>

    </v-container>
</template>

<script>
/*eslint-disable*/
import AuthorBooks from './AuthorBooks.vue';

export default {
  name: 'AuthorView',
  props: ['author_id'],
  components: {
    AuthorBooks
  },

  data: () => ({
      
  }),

  computed: {
    authors() {
      return this.$store.getters.getAuthors;
    },
    authId() {
      return this.$store.getters.authId;
    },
    loggedUser() {
      return this.$store.getters.loggedUser;
    }
  },

  created () {
    this.$store.dispatch('fetchOneAuthor', this.author_id);
  },

  methods: {
      followAuthor() {
      axios.post('/api/follow/addAuthor', {
        user_id: this.authId,
        author_id: parseInt(this.author_id)
      })
        .then((response) => {
          const Toast = this.$swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', this.$swal.stopTimer);
              toast.addEventListener('mouseleave', this.$swal.resumeTimer);
            }
          });

          Toast.fire({
            icon: 'success',
            title: 'Autor został dodany do listy ulubionych!'
          });
        })
        .catch((error) => {
          const Toast = this.$swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', this.$swal.stopTimer);
              toast.addEventListener('mouseleave', this.$swal.resumeTimer);
            }
          });

          Toast.fire({
            icon: 'error',
            title: 'Autor już istnieje na liście ulubionych!'
          });
        });
    }
  }
};
</script>