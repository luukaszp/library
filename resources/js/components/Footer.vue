<template>
    <div id="app">
        <v-footer color=#913608 style="text-align: center; margin-top: 15px" padless>
            <v-card
            flat
            tile
            class="white--text"
            style="background-color: #913608; width: 100%"
            >
            <v-card-text>
                <v-btn
                v-for="icon in icons"
                :key="icon.name"
                class="mx-4 white--text"
                icon
                :href="icon.link"
                >
                <v-icon size="24px">
                    {{ icon.name }}
                </v-icon>
                </v-btn>
            </v-card-text>

            <v-card-text class="white--text pt-0">
                <v-icon size="14px" class="white--text">mdi-copyright</v-icon> Wszelkie prawa zastrzeżone.
            </v-card-text>

            <v-divider style="margin-bottom: 0px"></v-divider>

            <v-card-text style="background-color: black" class="white--text">
                {{ new Date().getFullYear() }} — <strong>Biblioteka</strong>
            </v-card-text>
            </v-card>
        </v-footer>
    </div>
</template>

<script>
export default {
  name: 'Footer',
  data: () => ({
    icons: [
      { name: 'mdi-facebook', link: 'https://www.facebook.com/' },
      { name: 'mdi-twitter', link: 'https://www.twitter.com/' },
      { name: 'mdi-linkedin', link: 'https://www.linkedin.com/' },
      { name: 'mdi-instagram', link: 'https://www.instagram.com/' }
    ]
  })
};
</script>

<style scoped>
    a {
        text-decoration: none;
    }
</style>