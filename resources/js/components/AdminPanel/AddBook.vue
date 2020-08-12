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
                                    label="Tytuł"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="isbn"
                                    label="ISBN"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="description"
                                    label="Opis"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="publish_year"
                                    label="Rok wydania"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="cover"
                                    label="Okładka"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="author"
                                    label="Autorzy"
                                    outlined
                                    required
                            ></v-text-field>

                            <v-select
                                    class="pa-5 pb-0 pt-0"
                                    v-model="selectedCategory"
                                    :options="categories"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Kategoria"
                                    outlined
                                    required
                            ></v-select>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="publisher"
                                    label="Wydawnictwo"
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
                                    Dodaj książkę
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
                title: "",
                isbn: "",
                description: "",
                publish_year: "",
                cover: "",
                author: "",
                selectedCategory: "",
                publisher: "",
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
            },
            categories() {
                return this.$store.getters.getCategories;
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