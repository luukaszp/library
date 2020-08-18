<template>
    <div class="add">
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
                            <h2 class="pt-2" style="text-align: center">Dodawanie nowej książki</h2>

                            <hr>

                        <div class="upload">
                            <v-file-input
                                    class="pa-5 pb-0 pt-0"
                                    accept="image/*"
                                    v-model="cover"
                                    required
                                    :rules="coverRules"
                                    prepend-icon="mdi-book"
                                    :hide-input=true
                            ></v-file-input> <p>Wgraj okładkę książki</p>
                        </div>

                            <v-text-field
                                    class="pa-5 pb-0"
                                    v-model="title"
                                    label="Tytuł"
                                    outlined
                                    required
                                    :rules="titleRules"
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="isbn"
                                    label="ISBN"
                                    outlined
                                    required
                                    :rules="isbnRules"
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="description"
                                    label="Opis"
                                    outlined
                                    required
                                    :rules="descriptionRules"
                            ></v-text-field>

                            <v-text-field
                                    class="pa-5 pb-0 pt-0"
                                    v-model="publish_year"
                                    label="Rok wydania"
                                    outlined
                                    required
                                    :rules="publishYearRules"
                            ></v-text-field>

                            <v-select
                                    class="pa-5 pb-0 pt-0"
                                    v-model="selectedAuthor"
                                    :items="authors"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Autor"
                                    outlined
                                    required
                                    :rules="authorRules"
                            >
                            <template slot="item" slot-scope="data">
                                {{data.item.name}} {{data.item.surname}}
                            </template>
                            <template slot="selection" slot-scope="data">
                                {{data.item.name}} {{data.item.surname}}
                            </template>
                            </v-select>

                            <v-select
                                    class="pa-5 pb-0 pt-0"
                                    v-model="selectedCategory"
                                    :items="categories"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Kategoria"
                                    outlined
                                    required
                                    :rules="categoryRules"
                            ></v-select>

                            <v-select

                                    class="pa-5 pb-0 pt-0"
                                    v-model="selectedPublisher"
                                    :items="publishers"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Wydawnictwo"
                                    outlined
                                    required
                                    :rules="publisherRules"
                            ></v-select>

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
import axios from "axios";

    export default {
        name: "AddBook",
        data() {
            return {
                valid: false,
                value: true,
                title: "",
                isbn: "",
                description: "",
                publish_year: "",
                cover: [],
                selectedAuthor: "",
                selectedCategory: "",
                selectedPublisher: "",
                titleRules: [
                    v => !!v || 'Pole jest wymagane!',
                    v => v.length <= 60 || 'Tytuł jest za długi!',
                ],
                isbnRules: [
                    v => !!v || 'Pole jest wymagane!',
                    v => /^\d+$/.test(v) || 'Numer ISBN musi być prawidłowy',
                    v => v.length === 13 || 'Numer ISBN powinien zawierać 13 cyfr',
                ],
                descriptionRules: [
                    v => !!v || 'Pole jest wymagane!',
                    v => v.length >= 25 || 'Opis jest za krótki!',
                ],
                publishYearRules: [
                    v => !!v || 'Pole jest wymagane!',
                    v => /^\d+$/.test(v) || 'Rok wydania musi być prawidłowy',
                    v => v.length === 4 || 'Rok wydania powinien zawierać 4 cyfry',
                ],
                coverRules: [
                    v => !!v || 'Zdjęcie okładki książki jest wymagane!',
                    v => v.size < 2000000 || 'Zdjęcie okładki powinno być mniejsze niż 2MB!'
                ],
                authorRules: [
                    v => !!v || 'Wymagane jest wybranie autora!',
                ],
                categoryRules: [
                    v => !!v || 'Wymagane jest wybranie kategorii!',
                ],
                publisherRules: [
                    v => !!v || 'Wymagane jest wybranie wydawnictwa!',
                ]
            }
        },

        methods: {
            validate() {
                if(this.$refs.form.validate())
                {
                    let formData = new FormData();
                    formData.append("cover", this.cover);
                    formData.append("title", this.title);
                    formData.append("isbn", this.isbn);
                    formData.append("description", this.description);
                    formData.append("publish_year", this.publish_year);
                    formData.append("author", this.selectedAuthor);
                    formData.append("category", this.selectedCategory);
                    formData.append("publisher", this.selectedPublisher);

                    let config = {
                        headers: {
                            'Content-Type' : 'multipart/form-data'
                        }
                    }

                    axios.post('http://127.0.0.1:8000/api/store', formData, config)
                    .then(response => {
                    if(response.data.success == true) {
                        alert("Pomyślnie dodano książkę do bazy bibliotecznej!")
                        this.$router.push('/admin-panel/books')
                    }
                    else {
                        alert("Książka o podanych danych już isnieje.")
                    }
                })
                    .catch(error => {
                        console.log(error)
                    })
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
            },
            authors() {
                return this.$store.getters.getAuthors;
            },
            publishers() {
                return this.$store.getters.getPublishers;
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            },
        },

        created() {
            this.$store.dispatch("fetchCategories", {});
            this.$store.dispatch("fetchAuthors", {});
            this.$store.dispatch("fetchPublishers", {});
        },
    }
</script>

<style scoped>
    .upload {
        display: flex;
        width: 200px;
        justify-content: center;
        text-align: center;
    }
</style>