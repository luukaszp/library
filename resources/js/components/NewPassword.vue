<template>
    <div class="newpassword">
        <v-container>
            <v-row class="justify-center justify-md-center align-center">
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
                                md="6"
                        >
                            <h1 class="pt-8" style="text-align: center">Biblioteka</h1>
                            <h2 class="pt-2" style="text-align: center">Tworzenie nowego hasła</h2>

                            <hr>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    :value="password"
                                    label="Hasło"
                                    :type="'password'"
                                    :rules="[rules.password]"
                                    @input="_=>password=_"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pt-0"
                                    :value="password_confirmation"
                                    v-model="password_confirmation"
                                    label="Powtórz hasło"
                                    :type="'password'"
                                    :rules="confirmPasswordRules.concat(passwordConfirmationRule)"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-row class="pb-5 justify-center">

                                <v-btn
                                        :disabled="!valid"
                                        color=brown
                                        class="mr-5 mb-6"
                                        @click="validate"
                                >
                                    Zmień hasło
                                </v-btn>

                                <v-btn
                                        color=orange
                                        class="mr-5 mb-6"
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
  name: 'NewPassword',
  props: ['user_id'],
  data() {
    return {
      valid: false,
      value: true,
      password: '',
      password_confirmation: '',
      rules: {
        required: (value) => !!value || 'Hasło jest wymagane',
        password: (value) => {
          const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
          return (
            pattern.test(value) || '8 znaków, co najmniej jedna wielka litera, cyfra oraz znak specjalny'
          );
        }
      },
      confirmPasswordRules: [
        (v) => !!v || 'Potwierdzenie hasła jest wymagane'
      ]
    };
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        axios.post('/api/password-change', {
          user_id: this.$route.params.user_id,
          password: this.password,
          password_confirmation: this.password_confirmation
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
              title: 'Zmieniono hasło!'
            });
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    reset() {
      this.$refs.form.reset();
    }
  },

  computed: {
    passwordConfirmationRule() {
      return () => this.password === this.password_confirmation || 'Hasło musi być takie same';
    }
  }
};
</script>

<style scoped>

</style>