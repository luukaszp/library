<template>
        <div id="app">
                <v-card
                        flat
                        tile
                >
                    <v-app-bar
                            color=brown
                            dark
                    >

                        <v-app-bar-nav-icon>
                            <v-img alt="App logo" :src="require('../assets/app_logo_t.png')" width="90px"/> <!--zmienic logo-->
                        </v-app-bar-nav-icon>

                        <v-toolbar-title>Biblioteka</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <About/>

                        <router-link to="/admin-panel">
                            <v-btn icon>
                                <v-icon x-large>mdi-view-dashboard</v-icon>
                            </v-btn>
                        </router-link>
                        
                        <v-menu offset-y>
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on">
                                    <v-icon>mdi-account</v-icon>
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-item>
                                    <span v-if="isLoggedIn"><a @click="logout">Wyloguj się</a></span>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                    </v-app-bar>
                </v-card>
        </div>
</template>

<script>
    import About from "../components/About.vue";
    import AdminPanel from "../components/AdminPanel/AdminPanel.vue";
    export default {
        name: "Main",
        components: {
            About, AdminPanel
        },
        computed: {
            isLoggedIn: function(){ return this.$store.getters.isLoggedIn}
        },
        methods: {
            logout() {
                this.$store.dispatch('logout')
                    .then(() => this.$router.push({name: 'login'}))
            },
        }
    }
</script>

<style scoped>
</style>