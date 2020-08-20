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
                            <h2 class="pt-2" style="text-align: center">Wypożyczanie książek</h2>

                            <hr>

                            <v-select
                                    class="pa-5 pb-0"
                                    v-model="selectedReader"
                                    :items="readers"
                                    item-text="name"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Czytelnik"
                                    outlined
                                    required
                                    :rules="readerRules"
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
                                    v-model="selectedBooks"
                                    :items="books"
                                    item-text="title"
                                    item-value="id"
                                    menu-props="auto"
                                    label="Książki"
                                    outlined
                                    required
                                    :rules="booksRules"
                                    multiple
                            ></v-select>

                            <v-menu
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                            >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                v-model="date"
                                label="Wybierz datę wypożyczenia"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="date" @input="menu = false"></v-date-picker>
                            </v-menu>

                            <v-row class="pb-5 justify-center">

                                <v-btn
                                        :disabled="!valid"
                                        color=brown
                                        class="mr-5 mb-6"
                                        @click="validate"
                                >
                                    Wypożycz książkę
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
        name: "AddBorrow",
        data() {
            return {
                valid: false,
                value: true,
                selectedReader: "",
                selectedBooks: [],
                date: '',
                menu: false,
                readerRules: [
                    v => !!v || 'Wybranie czytelnika jest wymagane!',
                ],
                booksRules: [
                    v => !!v || 'Wymagane jest wybranie co najmniej jednej książki!',
                    v => v.length <= 5 || 'Maksymalnie można wybrać 5 książek',
                ]
            }
        },

        methods: {
            validate() {
                if(this.$refs.form.validate())
                {
                     this.$store.dispatch('borrowBooks', {
                        user_id: this.selectedReader,
                        book_id: this.selectedBooks,
                        borrows_date: this.date,
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
            readers() {
                return this.$store.getters.getReaders;
            },
            books() {
                return this.$store.getters.getBooks;
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            },
        },

        created() {
            this.$store.dispatch("fetchReaders", {});
            this.$store.dispatch("fetchAvailableBooks", {});
        },
    }
</script>

<style scoped>

</style>