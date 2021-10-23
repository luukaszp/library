<template>
    <v-row class="justify-center justify-md-center align-center">
        <v-dialog v-model="firstLoginDialog" max-width="600">
            <v-card style="overflow: hidden">
                <v-form
                    ref="form"
                    v-model="valid"
                >

                    <h1 class="pt-5" style="text-align: center">Witamy na stronie biblioteki!</h1>
                    <p class="pt-5 pb-2" style="text-align: center">W związku z Twoim pierwszym logowaniem, prosimy o zmianę hasła.</p>

                    <v-divider class="pt-5"></v-divider>

                    <v-text-field
                        class="pa-5 pb-0 pt-0"
                        v-model="email"
                        :rules="emailRules"
                        label="E-mail"
                        outlined
                        required
                    ></v-text-field>

                    <v-text-field
                        class="pa-5 pb-0 pt-0"
                        v-model="card_number"
                        :rules="cardNumberRules"
                        label="Numer karty bibliotecznej"
                        outlined
                        required
                    ></v-text-field>

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
                        :value="passwordConfirmation"
                        v-model="passwordConfirmation"
                        label="Powtórz hasło"
                        :type="'password'"
                        :rules="confirmPasswordRules.concat(passwordConfirmationRule)"
                        outlined
                        required
                    ></v-text-field>

                    <v-row class="buttons pb-5 justify-center">
                        <v-btn
                            :disabled="!valid"
                            color=#008D18
                            class="mr-5 mb-6"
                            @click="validate"
                        >
                            Zmień hasło
                        </v-btn>

                        <v-btn
                            color=#808080
                            class="mr-5 mb-6"
                            @click="reset"
                        >
                            Wyczyść dane
                        </v-btn>
                    </v-row>
                </v-form>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
  name: 'FirstLogin',
  data() {
    return {
      firstLoginDialog: true,
      password: '',
      card_number: '',
      valid: true,
      value: true,
      passwordConfirmation: '',
      email: '',
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
      ],
      cardNumberRules: [
        (v) => !!v || 'Numer karty bibliotecznej jest wymagany!',
        (v) => /^\d+$/.test(v) || 'Numer karty bibliotecznej musi być prawidłowy',
        (v) => v.length === 10 || 'Numer karty bibliotecznej powinien zawierać 10 cyfr'
      ],
      emailRules: [
        (v) => !!v || 'E-mail jest wymagany',
        (v) => /.+@.+\..+/.test(v) || 'E-mail musi być prawidłowy'
      ]
    };
  },

  computed: {
    passwordConfirmationRule() {
      return () => this.password === this.passwordConfirmation || 'Hasło musi być takie same';
    }
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        const data = {
          card_number: this.card_number,
          password: this.password,
          passwordConfirmation: this.passwordConfirmation,
          email: this.email
        };
        this.$store.dispatch('firstLogin', data)
          .catch((error) => {
            this.$swal('Błąd', 'Podano błędne dane.', 'error');
            console.log(error);
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
        .buttons {
            display: grid;
            padding-left: 15px;
        }
    }
</style>
