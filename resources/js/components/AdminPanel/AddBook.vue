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
                            <h1 class="pt-8">Biblioteka</h1>
                            <h2 class="pt-2">Dodawanie nowej książki</h2>

                            <hr>

                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="title"
                                    :rules="titleRules"
                                    label="Tytuł"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="isbn"
                                    :rules="isbnRules"
                                    label="ISBN"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>
                            
                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="description"
                                    :rules="descriptionRules"
                                    label="Opis"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="description"
                                    :rules="descriptionRules"
                                    label="Opis"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="publish_year"
                                    :rules="publishyearRules"
                                    label="Rok wydania"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="categories"
                                    :rules="categoryRules"
                                    label="Kategoria"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="authors"
                                    :rules="authorRules"
                                    label="Autorzy"
                                    single-line
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
        name: "Register",
        data() {
            return {
                valid: false,
                value: true,
                radios: "reader",
                name: "",
                surname: "",
                email: "",
                card_number: "",
                id_number: "",
                password: "",
                password_confirmation: "",
                rules: {
                    required: value => !!value || "Hasło jest wymagane",
                    password: value => {
                        const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
                        return (
                            pattern.test(value) ||
                            "8 znaków, co najmniej jedna wielka litera, cyfra oraz znak specjalny"
                        );
                    }
                },
                confirmPasswordRules: [
                    v => !!v || 'Potwierdzenie hasła jest wymagane',
                ],
                emailRules: [
                    v => !!v || 'E-mail jest wymagany',
                    v => /.+@.+\..+/.test(v) || 'E-mail musi być prawidłowy',
                ],
                nameRules: [
                    v => /^[a-zA-Z]+$/.test(v) || 'Imię powinno zawierać tylko litery',
                ],
                surnameRules: [
                    v => /^[a-zA-Z]+$/.test(v) || 'Nazwisko powinno zawierać tylko litery',
                ],
                cardNumberRules: [
                    v => /^\d+$/.test(v) || 'Numer karty bibliotecznej musi być prawidłowy',
                    v => v.length === 10 || 'Numer karty bibliotecznej powinien zawierać 10 cyfr',
                ]
            }
        },

        methods: {
            validate() {
                if(this.$refs.form.validate())
                {
                    this.$store.dispatch('userRegister', {
                        name: this.name,
                        surname: this.surname,
                        email: this.email,
                        card_number: this.card_number,
                        id_number: this.id_number,
                        password: this.password,
                        password_confirmation: this.password_confirmation
                    })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            reset() {
                this.$refs.form.reset()
            }
        },

        computed: {
            passwordConfirmationRule() {
                return () =>
                    this.password === this.password_confirmation || "Hasło musi być takie same";
            }
        },
    }
</script>

<style scoped>
    h1 {
        text-align: center;
    }

    h2 {
        text-align: center;
    }
</style>