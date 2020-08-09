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
                            <h2 class="pt-2">Tworzenie konta</h2>

                            <hr>

                            <div class="check">
                                <v-radio-group v-model="radios" :mandatory="true" row>
                                    <v-radio color="red" label="Konto czytelnika" value="reader" @click="changeValue()"></v-radio>
                                    <v-radio color="green" label="Konto pracownika" value="worker" @click="changeValue()"></v-radio>
                                </v-radio-group>
                            </div>

                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="name"
                                    :rules="nameRules"
                                    label="Imię"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="surname"
                                    :rules="surnameRules"
                                    label="Nazwisko"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>
                            
                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="email"
                                    :rules="emailRules"
                                    label="E-mail"
                                    single-line
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="card_number"
                                    :rules="checkReader ? [] : cardNumberRules"
                                    label="Numer karty bibliotecznej"
                                    :disabled="checkReader"
                                    single-line
                                    outlined
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="id_number"
                                    :rules="checkWorker ? [] : idNumberRules"
                                    label="Numer identyfikacyjny pracownika"
                                    :disabled="checkWorker"
                                    single-line
                                    outlined
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    :value="password"
                                    label="Hasło"
                                    :type="'password'"
                                    :rules="[rules.password]"
                                    @input="_=>password=_"
                                    single-line
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
                                    Zarejestruj użytkownika
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
                checkReader: false,
                checkWorker: true,
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
                ], 
                idNumberRules: [
                    v => /^\d+$/.test(v) || 'Identyfikator musi być prawidłowy',
                    v => v.length === 12 || 'Identyfikator powinien zawierać 12 cyfr',
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
            },
            changeValue() {
                if(this.radios === 'reader') {
                    this.id_number = ''
                    this.checkReader = false 
                    this.checkWorker = true
                }
                else {
                    this.card_number = ''
                    this.checkReader = true 
                    this.checkWorker = false
                }
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

    .check {
        justify-content: center;
        display: flex;
    }
</style>