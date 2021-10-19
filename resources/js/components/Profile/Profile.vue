<template>
    <v-container>
        <v-row style="margin-left: 10px; margin-right: 10px">
            <v-col md=3 style="justify-content: center; text-align: center;">
                <v-avatar size="200">
                    <v-img class="card-img" :src="'https://library-site.s3.eu-north-1.amazonaws.com/avatars/' + readers.avatar"></v-img>
                </v-avatar>

                <v-form
                    ref="form"
                    v-model="valid"
                >
                <div class="upload" style="padding-top: 15px" v-if="this.$route.params.user_id.toString() === this.authId.toString()">
                    <v-btn
                        color="#008D18"
                        class="white--text text-none pa-5 pb-0 pt-0"
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
                <p>Email: <span style="font-weight: bold; color: #008D18">{{readers.email}}</span></p>
                <p>Członek biblioteki od: <span style="font-weight: bold; color: #008D18">{{readers.created_at.slice(0,10)}}</span></p>
                <p>Numer karty czytelniczej: <span style="font-weight: bold; color: #008D18">{{readers.readers.card_number}}</span></p>
                <p v-if="readers.readers.can_extend === '0'">Termin przedłużenia książki: <span style="font-weight: bold; color: red">WYKORZYSTANO</span></p>
                <p v-else>Termin przedłużenia książki: <span style="font-weight: bold; color: #008D18">MOŻNA PRZEDŁUŻYĆ</span></p>
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
            'Content-Type': 'multipart/form-data',
          }
        };

        axios({
            method: 'post',
            url: '/api/user/profile/upload',
            data: {
                data: formData
            },
            config,
            validateStatus: (status) => {
                return true;
            },
        }).catch((error) => {
            console.log(error);
        }).then(response => {
            if (response.data.success == true) {
              this.$swal('Zmieniono', 'Pomyślnie zmieniono awatar!', 'success');
            } else {
              this.$swal('Błąd', 'Zmiana awataru zakończyła się niepowodzeniem!', 'error');
            }
        });

        /*axios.post('/api/user/profile/upload', formData, config)
          .then((response) => {
            if (response.data.success == true) {
              this.$swal('Zmieniono', 'Pomyślnie zmieniono awatar!', 'success');
            } else {
              this.$swal('Błąd', 'Zmiana awataru zakończyła się niepowodzeniem!', 'error');
            }
          })
          .catch((error) => {
            console.log(error);
          });*/
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
