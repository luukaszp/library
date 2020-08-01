<template>
        <div id="app">
                <v-card
                        flat
                        tile
                >
                    <v-app-bar
                            color="#3eb4a7"
                            dark
                    >

                        <v-toolbar-side-icon>
                            <v-img alt="App logo" src="../assets/app_logo_t.png" width="90px"/> <!--zmienic logo-->
                        </v-toolbar-side-icon>

                        <v-toolbar-title>Budget Tracker</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <About/>

                        <v-menu offset-y>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on">
                                    <v-icon>mdi-account</v-icon>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item @click="logout">
                                    <v-list-item-title @click="logout">Wyloguj się</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                    </v-app-bar>
                </v-card>
        </div>
</template>

<script>
    import About from "../components/About.vue";
    export default {
        name: "Main",
        components: {
            About
        },
        methods: {
            logout() {
                this.$store.dispatch('destroyToken')
                    .then(() => this.$router.push({name: 'login'}))
            },
        }
    }
</script>

<style scoped>
</style>