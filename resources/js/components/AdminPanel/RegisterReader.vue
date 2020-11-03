<template>
    <div class="register">
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
                            <h2 class="pt-2" style="text-align: center">Tworzenie konta czytelnika</h2>

                            <hr>

                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="name"
                                    :rules="nameRules"
                                    label="Imię"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="surname"
                                    :rules="surnameRules"
                                    label="Nazwisko"
                                    outlined
                                    required
                            ></v-text-field>

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
                                    v-model="card_number"
                                    label="Hasło"
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
                                    Zarejestruj czytelnika
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
export default {
  name: 'RegisterReader',
  data() {
    return {
      valid: false,
      value: true,
      name: '',
      surname: '',
      email: '',
      card_number: '',
      id_number: '',
      password: '',
      password_confirmation: '',
      emailRules: [
        (v) => !!v || 'E-mail jest wymagany',
        (v) => /.+@.+\..+/.test(v) || 'E-mail musi być prawidłowy'
      ],
      nameRules: [
        (v) => !!v || 'Imię jest wymagane!',
        (v) => /^[a-zA-Z]+$/.test(v) || 'Imię powinno zawierać tylko litery'
      ],
      surnameRules: [
        (v) => !!v || 'Nazwisko jest wymagane!',
        (v) => /^[a-zA-Z]+$/.test(v) || 'Nazwisko powinno zawierać tylko litery'
      ],
      cardNumberRules: [
        (v) => !!v || 'Numer karty bibliotecznej jest wymagany!',
        (v) => /^\d+$/.test(v) || 'Numer karty bibliotecznej musi być prawidłowy',
        (v) => v.length === 10 || 'Numer karty bibliotecznej powinien zawierać 10 cyfr'
      ]
    };
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        const data = {
          name: this.name,
          surname: this.surname,
          email: this.email,
          card_number: this.card_number,
          id_number: this.id_number,
          password: this.card_number,
          password_confirmation: this.card_number
        };
        this.$store.dispatch('userRegister', data)
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
              title: 'Dodano nowego czytelnika!'
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
  }
};
</script>

<style scoped>

</style>