<template>
    <div class="reset">
        <v-container>
            <v-row class="justify-center justify-md-center align-center">
                <v-row class="justify-lg-end justify-sm-center pr-5 d-none d-sm-flex col-md-5">
                    <v-img
                            alt="App Logo"
                            contain
                            class="shrink"
                            :src="require('../assets/app_logo.png')"
                    />
                </v-row>
                <v-divider
                        vertical
                ></v-divider>
                <v-col
                        cols="12"
                        md="6"
                >

                    <v-card
                            class="ma-2"
                            max-width="600"
                    >
                        <v-form
                                ref="form"
                                v-model="valid"
                                md="7"
                        >
                            <h1 class="pt-8" style="text-align: center">Biblioteka</h1>
                            <h2 class="pt-2 pb-5" style="text-align: center">Resetowanie hasła</h2>

                            <v-text-field
                                    class="pa-5 pb-0 pt-5"
                                    v-model="email"
                                    :rules="emailRules"
                                    label="E-mail"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-row class="pb-5 justify-center" id="buttons">

                                <v-btn
                                        :disabled="!valid"
                                        color=#008D18
                                        class="white--text mr-5 mb-6 font-weight-bold"
                                        @click="validate"
                                >
                                    Resetuj hasło
                                </v-btn>

                                <v-btn
                                        color=#808080
                                        class="white--text mr-5 mb-6 font-weight-bold"
                                        @click="reset"
                                >
                                    Wyczyść dane
                                </v-btn>
                            </v-row>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PasswordReset',
  data() {
    return {
      valid: false,
      value: true,
      email: '',
      emailRules: [
        (v) => !!v || 'E-mail jest wymagany',
        (v) => /.+@.+\..+/.test(v) || 'E-mail musi być prawidłowy'
      ]
    };
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        axios.post('/api/password-reset', {
          email: this.email
        })
          .then(() => {
            const Toast = this.$swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', this.$swal.stopTimer);
                toast.addEventListener('mouseleave', this.$swal.resumeTimer);
              }
            });

            Toast.fire({
              icon: 'success',
              title: 'E-mail został wysłany!'
            });
          })
          .catch(() => {
            this.$swal('Błąd', 'Użytkownik o podanym e-mailu nie istnieje!', 'error');
          });
      }
    },
    reset() {
      this.$refs.form.reset();
    }
  }
};
</script>

<style scoped>
    @media only screen and (max-width: 600px) {
        #buttons {
            margin: 0;
        }
    }
</style>
