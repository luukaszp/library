<template>
    <div class="text-center">
        <v-dialog
                v-model="dialog"
                width="500"
        >
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on">
                    <v-icon x-large>mdi-information</v-icon>
                </v-btn>
            </template>

            <v-card>
                <v-toolbar color="#9090ee">
                    <v-toolbar-title class="headline lighten-2 white--text">O aplikacji</v-toolbar-title>
                </v-toolbar>

                <v-card-text class="pt-5 text-center">
                    <strong>Aplikacja ma na celu stworzenie miejsca, w którym użytkownik będzie mógł prowadzić swój własny wyciąg wydatków i przychodów. Dzięki tej aplikacji, pod koniec miesiąca będzie można ustalić, gdzie podziała się cała wypłata oraz czy wydajemy na dany cel tyle, ile faktycznie chcieliśmy wydać. Dodatkowo wyświetlane będzie ogólne podsumowanie oraz statystyki miesięczne.</strong>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "About",
        data () {
            return {
                dialog: false,
            }
        },
    }
</script>

<style scoped>

</style>