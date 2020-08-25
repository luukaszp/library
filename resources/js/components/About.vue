<template>
    <div class="text-center">
        <v-dialog
                v-model="dialog"
                width="500"
        >
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on">
                    <v-icon x-large>mdi-information-outline</v-icon>
                </v-btn>
            </template>

            <v-card>
                <v-toolbar color="#9090ee">
                    <v-toolbar-title class="headline lighten-2 white--text">O aplikacji</v-toolbar-title>
                </v-toolbar>

                <v-card-text class="pt-5 text-center">
                    <strong>Biblioteka</strong>
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