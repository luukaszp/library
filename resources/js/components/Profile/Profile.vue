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
                    <v-form ref="form" v-model="form1" lazy-validation>
                      <v-file-input
                          v-model="avatar"
                          accept="image/png, image/jpeg"
                          label="Pick a Picture"
                          outlined
                          dense
                          prepend-icon=""
                          prepend-inner-icon="mdi-camera"
                          hide-details
                        >
                        </v-file-input>
                        <v-btn
                          color="primary"
                          rounded
                          block
                          class="mt-3"
                          @click="validate"
                          :disabled="!form1"
                        >
                          Submit
                        </v-btn>
                    </v-form>
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
    avatar: null,
    valid: false,
    form1: false
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
        //const formData = new FormData();
        const blob = this.avatar
        //const FormData = require('form-data')
        const formData = new FormData();
        //const form = new FormData();
        formData.append('avatar', blob)
        formData.append('user_id', parseInt(this.user_id));

        const config = {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        };
        /*let url = 'https://library-site.herokuapp.com/api/user/profile/upload';
        let request = new XMLHttpRequest();
        request.open('POST', url);
        request.send(form);*/
        //axios.defaults.headers.common['Content-Type'] = 'multipart/form-data';

        axios.post('/api/user/profile/upload', formData)
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

          /*
          const request = require('request');
        const formData = {
          avatar: this.avatar
        }
        console.log(formData);
        request.post({url:'http://localhost:8080/api/user/profile/upload', formData: formData})
        */
      }
    },
    /*onButtonClick() {
      this.isSelecting = true
      window.addEventListener('focus', () => {
        this.isSelecting = false
      }, { once: true })

      this.$refs.uploader.click()
    },
    onFileChanged(e) {
      this.avatar = e.target.files[0]
      this.validate();
    }*/
  }
};
</script>
