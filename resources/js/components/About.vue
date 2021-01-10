<template>
    <div class="text-center">
        <v-dialog
                v-model="dialog"
                width="500"
        >
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on">
                    <v-icon large>mdi-information-outline</v-icon>
                </v-btn>
            </template>

            <v-card>
                <v-toolbar color="#9090ee">
                    <v-toolbar-title class="headline lighten-2 white--text">O aplikacji</v-toolbar-title>
                </v-toolbar>

                <v-card-text class="pt-5 text-center">
                    <strong>Aplikacja webowa służy do wspomagania funkcjonowania biblioteki oraz usprawnienia jej
                        działania. Oferuje ona wiele przydatnych opcji dla pracowników placówki w kwestii zarządzania
                        danymi. Czytelnicy natomiast mogą przeglądać pozycje książkowe znajdujące się w bibliotece, dodatkowo
                        mogą obserwować ulubionych autorów w celu otrzymania powiadomienia o dodaniu nowej książki jednego z nich.
                        Oprócz tego do dyspozycji mają statystyki konta, ranking miesięczny czytelników czy kalendarz wydarzeń.</strong>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
  name: 'About',
  data () {
    return {
      dialog: false
    };
  }
};
</script>

<style scoped>

</style>