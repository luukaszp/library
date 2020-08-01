<template>
    <div id="login" class="login">
        <v-container>
            <v-row class="logform">
                <v-row class="justify-lg-end justify-sm-center pr-5 d-none d-sm-flex">
                    <v-img
                            alt="App Logo"
                            contain
                            class="shrink"
                            :src="require('../assets/app_logo.png')"
                    /> <!-- zmienic logo -->
                </v-row>
                <v-divider
                        vertical
                ></v-divider>
                <v-col
                        cols="12"
                        md="7"
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
                            <h1 class="pt-8">Budget Tracker</h1>

                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="email"
                                    :rules="emailRules"
                                    label="E-mail"
                                    single-line
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
                                    single-line
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
                                    Zaloguj się
                                </v-btn>

                                <v-btn
                                        color=#3eb4a7
                                        class="mr-5 mb-6"
                                        @click="reset"
                                >
                                    Wyczyść dane
                                </v-btn>
                            </v-row>

                            <hr>

                            <v-row class="pt-7 pb-7 justify-center">
                                <p class="pr-5 pt-2">Nie masz konta?</p>

                                    <v-btn
                                            outlined
                                            to="/register"
                                            color="#3eb4a7"
                                    >
                                        Zarejestruj się
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
        name: "Login",
        data() {
            return {
                password: "",
                valid: true,
                value: true,
                email: "",
                emailRules: [
                    v => !!v || 'E-mail jest wymagany',
                    v => /.+@.+\..+/.test(v) || 'E-mail musi być prawidłowy',
                ],
                rules: {
                    required: value => !!value || "Hasło jest wymagane",
                    password: value => {
                        const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
                        return (
                            pattern.test(value) ||
                            "8 znaków, co najmniej jedna wielka litera, cyfra oraz znak specjalny"
                        );
                    }
                }
            }
        },
        methods: {
            validate() {
                if(this.$refs.form.validate())
                {
                    this.$store.dispatch('getToken', {
                        email: this.email,
                        password: this.password
                    })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            reset() {
                this.$refs.form.reset()
            },
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
    .logform
    {
        margin-top: 50px;
    }
    @media only screen and (min-width: 600px) and (max-width: 960px) {
        .form {
            margin-left: 10%;
        }
    }
</style>