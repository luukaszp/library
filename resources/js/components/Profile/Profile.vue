<template>
    <v-container>
        <v-row style="margin-left: 10px; margin-right: 10px">
            <v-col md=3 style="justify-content: center; text-align: center;">
                <v-avatar size="200">
                    <v-img class="card-img" v-bind:src="('../storage/' + readers.avatar)"></v-img>
                </v-avatar>

                <v-form
                    ref="form"
                    v-model="valid"
                >
                <div class="upload" style="padding-top: 15px" v-if="this.$route.params.user_id.toString() === this.authId.toString()">
                    <v-btn
                        color="primary"
                        class="text-none pa-5 pb-0 pt-0"
                        rounded
                        depressed
                        :loading="isSelecting"
                        v-model="avatar"
                        @click="onButtonClick"
                    >
                        <v-icon left>
                            mdi-camera-account
                        </v-icon>
                        {{ buttonText }}
                    </v-btn>
                    <input
                        ref="uploader"
                        required
                        class="d-none"
                        type="file"
                        accept="image/*"
                        @change="onFileChanged"
                    >
                </div>
                </v-form>

            </v-col>
            <v-col style="text-align: left">
                <h2>{{readers.name + ' ' + readers.surname}}</h2>
                <v-divider></v-divider>
                <p>Numer karty bibliotecznej: {{readers.card_number}}</p>
                <p>Email: {{readers.email}}</p>
            </v-col>
        </v-row>

        <v-divider></v-divider>

        <v-row style="margin-left: 10px; margin-right: 10px; justify-content: center;" v-if="this.$route.params.user_id.toString() === this.authId.toString()">
            <ProfileTabs v-bind:user_id="user_id"/>
        </v-row>

    </v-container>
</template>

<script>
/*eslint-disable*/
import ProfileTabs from './ProfileTabs.vue';

export default {
  name: 'Profile',
  props: ['user_id'],
  components: {
    ProfileTabs
  },
  
  data: () => ({
    isSelecting: false,
    defaultButtonText: 'Wgraj awatar',
    avatar: [],
    valid: false
  }),

  computed: {
    readers() {
      return this.$store.getters.getReaders;
    },
    buttonText() {
      return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
    },
    authId() {
      return this.$store.getters.authId;
    }
  },

  created () {
    this.$store.dispatch('fetchOneReader', this.user_id);
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        const formData = new FormData();
        formData.append('avatar', this.avatar);
        formData.append('user_id', parseInt(this.user_id));

        const config = {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        };

        axios.post('http://127.0.0.1:8000/api/user/profile/upload', formData, config)
          .then((response) => {
            if (response.data.success == true) {
              this.$swal('Zmieniono', 'Pomyślnie zmieniono awatar!', 'success');
            } else {
              this.$swal('Błąd', 'Zmiana awataru zakończyła się niepowodzeniem!', 'error');
            }
          })
          .catch((error) => {
            console.log(error);
          });
          this.$store.dispatch('fetchOneReader', this.user_id);
      }
    },
    onButtonClick() {
      this.isSelecting = true
      window.addEventListener('focus', () => {
        this.isSelecting = false
      }, { once: true })

      this.$refs.uploader.click()
    },
    onFileChanged(e) {
      this.avatar = e.target.files[0]
      this.validate();
    }
  }
};
</script>