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

                    <v-row class="pb-5 justify-center">
                        <v-btn
                            :disabled="!valid"
                            color=#9090ee
                            class="mr-5 mb-6"
                            @click="validate"
                        >
                            Zmień hasło
                        </v-btn>

                        <v-btn
                            color=#3eb4a7
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
  props: ['user_id'],
  data() {
    return {
      firstLoginDialog: true,
      password: '',
      valid: true,
      value: true,
      passwordConfirmation: '',
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

  computed: {
    passwordConfirmationRule() {
      return () => this.password === this.passwordConfirmation || 'Hasło musi być takie same';
    },
    authId() {
      return this.$store.getters.authId;
    },
    readers() {
      return this.$store.getters.getReaders;
    }
  },

  created () {
    if (this.$route.params.user_id !== this.authId.toString()) {
      this.$swal('Nieautoryzowany', 'Odmowa dostępu!', 'error');
      this.firstLoginDialog = false;
      this.$router.push('/');
    }
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        const data = {
          user_id: this.authId,
          password: this.password,
          passwordConfirmation: this.passwordConfirmation
        };
        this.$store.dispatch('passwordChange', data)
          .catch((error) => {
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