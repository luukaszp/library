<template>
            <v-data-table
                    :headers="headers"
                    :items="categories"
                    :search="search"
                    sort-by="amount"
                    class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Kategorie</v-toolbar-title>
                        <v-divider
                                class="mx-4"
                                inset
                                vertical
                        ></v-divider>
                        <v-spacer></v-spacer>
                        <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                                class="mr-3"
                        ></v-text-field>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="addCategoryDialog" max-width="300px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="#3eb4a7" dark class="mb-2" v-on="on">Nowa kategoria</v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col>
                                                <v-text-field v-model="editedItem.category" label="Kategoria"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                                    <v-btn color="blue darken-1" text @click="addCategory">Zapisz</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.action`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editCategory(item)"
                        >
                        mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteCategory(item)"
                        >
                        mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak kategorii</p>
                    </template>

            </v-data-table>
</template>

<script>
    export default {
        name: "Categories",
        data: () => ({
            addCategoryDialog: false,
            editCategoryDialog: false,
            search: '',
            choosedType: '',
            categoryName: '',
            headers: [
                {
                    text: 'Kategorie',
                    align: 'start',
                    value: 'category',
                },
                {text: 'Akcje', value: 'action', sortable: false},
            ],
            editedIndex: -1,
            editedItem: {
                category: '',
            },
            defaultItem: {
                category: '',
            },
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'Nowa kategoria' : 'Edytuj kategorie'
            },
            categories() {
                return this.$store.getters.getCategories;
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            },
        },

        created() {
            this.$store.dispatch("fetchCategories", {});
        },

        methods: {
            editCategory(item) {
                this.editedIndex = this.categories.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.addCategoryDialog = true
            },

            deleteCategory(item) {
                const index = this.categories.indexOf(item)
                if (confirm('Czy jesteś pewien, że chcesz usunąć tę kategorię?')) {
                    axios.delete('/api/category/delete/'+ item.id, {})
                        this.categories.splice(index, 1)
                }
            },

            addCategory() {
                if (this.editedIndex > -1) {
                    Object.assign(this.categories[this.editedIndex], this.editedItem)
                    axios.put('/api/category/edit/'+ this.editedItem.id, {
                        category: this.editedItem.category
                    })
                } else {
                    this.categories.push(this.editedItem)
                    axios.post('/api/category/add', {
                        category: this.editedItem.category
                    })
                        .catch(error => {
                            console.log(error)
                        })
                }
                this.close()
            },

            close() {
                this.addCategoryDialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },
        },
    }
</script>

<style scoped>

</style>