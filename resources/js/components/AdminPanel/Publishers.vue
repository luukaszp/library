<template>
            <v-data-table
                    :headers="headers"
                    :items="publishers"
                    :search="search"
                    sort-by="amount"
                    class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Wydawnictwa</v-toolbar-title>
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
                        <v-dialog v-model="addPublisherDialog" max-width="300px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="#3eb4a7" dark class="mb-2" v-on="on">Nowe wydawnictwo</v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col>
                                                <v-text-field v-model="editedItem.name" label="Wydawnictwo"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close">Anuluj</v-btn>
                                    <v-btn color="blue darken-1" text @click="addPublisher">Zapisz</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                    <template v-slot:[`item.action`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editPublisher(item)"
                        >
                        mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deletePublisher(item)"
                        >
                        mdi-delete
                        </v-icon>
                    </template>

                    <template v-slot:no-data>
                        <p class="pt-5">Brak wydawnictw</p>
                    </template>

            </v-data-table>
</template>

<script>
    export default {
        name: "Publishers",
        data: () => ({
            addPublisherDialog: false,
            search: '',
            publisherName: '',
            headers: [
                {
                    text: 'Wydawnictwa',
                    align: 'start',
                    value: 'name',
                },
                {text: 'Akcje', value: 'action', sortable: false},
            ],
            editedIndex: -1,
            editedItem: {
                name: '',
            },
            defaultItem: {
                name: '',
            },
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'Nowe wydawnictwo' : 'Edytuj wydawnictwo'
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
            this.$store.dispatch("fetchPublishers", {});
        },

        methods: {
            editPublisher(item) {
                this.editedIndex = this.publishers.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.addPublisherDialog = true
            },

            deletePublisher(item) {
                const index = this.publishers.indexOf(item)
                if (confirm('Czy jesteś pewien, że chcesz usunąć te wydawnictwo?')) {
                    axios.delete('/api/publisher/delete/'+ item.id, {})
                        this.publishers.splice(index, 1)
                }
            },

            addPublisher() {
                if (this.editedIndex > -1) {
                    Object.assign(this.publishers[this.editedIndex], this.editedItem)
                    axios.put('/api/publisher/edit/'+ this.editedItem.id, {
                        name: this.editedItem.name
                    })
                } else {
                    this.publishers.push(this.editedItem)
                    axios.post('/api/publisher/add', {
                        name: this.editedItem.name
                    })
                        .catch(error => {
                            console.log(error)
                        })
                }
                this.close()
            },

            close() {
                this.addPublisherDialog = false
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