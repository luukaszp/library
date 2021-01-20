<template>
    <div id="login" class="login">
        <v-container>
            <v-row class="logform" style="margin-top: 50px">
                <v-row class="justify-lg-end justify-sm-center pr-5 d-none d-sm-flex">
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
                        class="form"
                >

                    <v-card
                            class="ma-2"
                            max-width="600"
                    >
                        <v-form
                                ref="form"
                                v-model="valid"
                                md="6"
                        >
                            <h1 class="pt-8" style="text-align: center">Biblioteka</h1>

                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="login"
                                    :rules="loginRules"
                                    label="Numer karty bibliotecznej"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pt-0"
                                    :value="password"
                                    label="Hasło"
                                    :append-icon="value ? 'mdi-eye' : 'mdi-eye-off'"
                                    @click:append="() => (value = !value)"
                                    :type="value ? 'password' : 'text'"
                                    :rules="[rules.password]"
                                    @input="_=>password=_"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-row class="pb-5 justify-center">
                                <v-btn
                                        :disabled="!valid"
                                        color=#008D18
                                        class="white--text mr-5 mb-6 font-weight-bold"
                                        @click="validate"
                                >
                                    Zaloguj się
                                </v-btn>

                                <v-btn
                                        color=#808080
                                        class="white--text mr-5 mb-6 font-weight-bold"
                                        @click="reset"
                                >
                                    Wyczyść dane
                                </v-btn>
                            </v-row>
                            <v-row class="pb-5 justify-center">
                                <router-link :to="{ name: 'reset-password'}" style="text-decoration: none; color: #008D18">
                                    <span style="font-weight: bold">Nie pamiętam hasła</span>
                                </router-link>
                            </v-row>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
/* eslint-disable */
export default {
  name: 'Login',
  data() {
    return {
      password: '',
      valid: true,
      value: true,
      login: '',
      loginRules: [
        (v) => !!v || 'Login jest wymagany',
        (v) => /^\d+$/.test(v) || 'Login musi być prawidłowy',
        (v) => v.length >= 10 && v.length <= 12 || 'Login powinien zawierać odpowiednią ilość cyfr'
      ],
      rules: {
        required: (value) => !!value || 'Hasło jest wymagane',
        password: (value) => {
          const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
          return (
            pattern.test(value) || '8 znaków, co najmniej jedna wielka litera, cyfra oraz znak specjalny'
          );
        }
      }
    };
  },
  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        const data = {
          login: this.login,
          password: this.password
        };
        this.$store.dispatch('login', data)
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
              title: 'Zalogowano!'
            });
          })
          .catch((error) => {
            this.$swal('Błąd', 'Nieprawidłowy login lub hasło!', 'error');
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
    @media only screen and (min-width: 600px) and (max-width: 960px) {
        .form {
            margin-left: 10%;
        }
    }
</style>